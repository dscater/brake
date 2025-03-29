<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Concepto;
use App\Models\Egreso;
use App\Models\HistorialAccion;
use App\Models\Ingreso;
use App\Models\IngresoDetalle;
use App\Models\Producto;
use App\Services\HistorialAccionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:2",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
    ];

    private $modulo = "CATEGORÍAS";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function index()
    {
        return Inertia::render("Categorias/Index");
    }

    public function listado(Request $request)
    {
        $categorias = Categoria::select("categorias.*");

        if (isset($request->byTipo) && $request->byTipo) {
            $categorias->where("tipo", $request->byTipo);
        }

        if ($request->order && $request->order == "desc") {
            $categorias->orderBy("categorias.id", $request->order);
        }

        $categorias = $categorias->get();

        return response()->JSON([
            "categorias" => $categorias
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $categorias = Categoria::select("categorias.*");
        if (trim($search) != "") {
            $categorias->where("nombre", "LIKE", "%$search%");
        }

        $categorias = $categorias->paginate($request->itemsPerPage);
        return response()->JSON([
            "categorias" => $categorias
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Categoria
            $nuevo_categoria = Categoria::create(array_map('mb_strtoupper', $request->all()));

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA CATEGORÍA", $nuevo_categoria);

            DB::commit();
            return redirect()->route("categorias.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Categoria $categoria) {}

    public function update(Categoria $categoria, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $old_categoria = Categoria::find($categoria->id);
            $categoria->update(array_map('mb_strtoupper', $request->all()));
            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA CATEGORÍA", $old_categoria, $categoria);

            DB::commit();
            return redirect()->route("categorias.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(Categoria $categoria)
    {
        DB::beginTransaction();
        try {
            $usos = Concepto::where("categoria_id", $categoria->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar esta categoría porque esta siendo utilizada por otros registros",
                ]);
            }
            $usos = Ingreso::where("categoria_id", $categoria->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar esta categoría porque esta siendo utilizada por otros registros",
                ]);
            }
            $usos = Egreso::where("categoria_id", $categoria->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar esta categoría porque esta siendo utilizada por otros registros",
                ]);
            }

            $old_categoria = Categoria::find($categoria->id);
            $categoria->delete();

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA CATEGORÍA", $old_categoria);

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
