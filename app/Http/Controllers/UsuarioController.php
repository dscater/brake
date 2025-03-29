<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Obra;
use App\Models\User;
use App\Services\HistorialAccionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:1",
        "paterno" => "required|min:1",
        "ci" => "required|min:1",
        "ci_exp" => "required",
        "dir" => "required|min:1",
        "fono" => "required|min:1",
        "tipo" => "required",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
        "paterno.required" => "Este campo es obligatorio",
        "paterno.min" => "Debes ingresar al menos :min caracteres",
        "ci.required" => "Este campo es obligatorio",
        "ci.unique" => "Este C.I. ya fue registrado",
        "ci.min" => "Debes ingresar al menos :min caracteres",
        "ci_exp.required" => "Este campo es obligatorio",
        "dir.required" => "Este campo es obligatorio",
        "dir.min" => "Debes ingresar al menos :min caracteres",
        "fono.required" => "Este campo es obligatorio",
        "fono.min" => "Debes ingresar al menos :min caracteres",
        "tipo.required" => "Este campo es obligatorio",
    ];

    private $modulo = "USUARIOS";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function index()
    {
        return Inertia::render("Usuarios/Index");
    }

    public function listado()
    {
        $usuarios = User::where("id", "!=", 1)->get();
        return response()->JSON([
            "usuarios" => $usuarios
        ]);
    }

    public function byTipo(Request $request)
    {
        $usuarios = User::where("id", "!=", 1);
        if (isset($request->tipo) && trim($request->tipo) != "") {
            $usuarios = $usuarios->where("tipo", $request->tipo);
        }

        if ($request->order && $request->order == "desc") {
            $usuarios->orderBy("users.id", "desc");
        }

        $usuarios = $usuarios->get();

        return response()->JSON([
            "usuarios" => $usuarios
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $usuarios = User::where("id", "!=", 1);

        if (trim($search) != "") {
            $usuarios->where("nombre", "LIKE", "%$search%");
            $usuarios->orWhere("paterno", "LIKE", "%$search%");
            $usuarios->orWhere("materno", "LIKE", "%$search%");
            $usuarios->orWhere("ci", "LIKE", "%$search%");
        }

        $usuarios = $usuarios->paginate($request->itemsPerPage);
        return response()->JSON([
            "usuarios" => $usuarios
        ]);
    }

    public function store(Request $request)
    {
        $this->validacion['ci'] = 'required|min:4|numeric|unique:users,ci';
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:2048';
        }
        $request->validate($this->validacion, $this->mensajes);

        $cont = 0;
        do {
            $nombre_usuario = User::getNombreUsuario($request->nombre, $request->paterno);
            if ($cont > 0) {
                $nombre_usuario = $nombre_usuario . $cont;
            }
            $request['usuario'] = $nombre_usuario;
            $cont++;
        } while (User::where('usuario', $nombre_usuario)->get()->first());

        $request['password'] = 'NoNulo';
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Usuario
            $nuevo_usuario = User::create(array_map('mb_strtoupper', $request->except('foto')));
            $nuevo_usuario->password = Hash::make($request->ci);
            $nuevo_usuario->save();
            if ($request->hasFile('foto')) {
                $file = $request->foto;
                $nom_foto = time() . '_' . $nuevo_usuario->usuario . '.' . $file->getClientOriginalExtension();
                $nuevo_usuario->foto = $nom_foto;
                $file->move(public_path() . '/imgs/users/', $nom_foto);
            }
            $nuevo_usuario->save();

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN USUARIO", $nuevo_usuario);

            DB::commit();
            return redirect()->route("usuarios.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(User $user) {}

    public function update(User $user, Request $request)
    {
        $this->validacion['ci'] = 'required|min:4|numeric|unique:users,ci,' . $user->id;
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:2048';
        }
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $old_user = clone $user;

            $user->update(array_map('mb_strtoupper', $request->except('foto')));
            if ($request->hasFile('foto')) {
                $antiguo = $user->foto;
                if ($antiguo != 'default.png') {
                    \File::delete(public_path() . '/imgs/users/' . $antiguo);
                }
                $file = $request->foto;
                $nom_foto = time() . '_' . $user->usuario . '.' . $file->getClientOriginalExtension();
                $user->foto = $nom_foto;
                $file->move(public_path() . '/imgs/users/', $nom_foto);
            }
            $user->save();

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN USUARIO", $old_user, $user);


            DB::commit();
            return redirect()->route("usuarios.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function actualizaPassword(User $user, Request $request)
    {
        $request->validate([
            "password" => "required"
        ]);
        DB::beginTransaction();
        try {
            $old_user = clone $user;
            $datos_original = HistorialAccion::getDetalleRegistro($user, "users");
            $user->password = Hash::make($request->password);
            $user->save();
            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ LA CONTRASEÑA DE UN USUARIOf", $old_user, $user);

            DB::commit();
            return redirect()->route("usuarios.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $old_user = clone $user;

            $user->historial_accions()->delete();

            $antiguo = $user->foto;
            if ($antiguo != 'default.png') {
                \File::delete(public_path() . '/imgs/users/' . $antiguo);
            }

            $user->delete();

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UN USUARIO", $old_user);

            DB::commit();
            return response()->JSON([
                'sw' => true,
                'message' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
