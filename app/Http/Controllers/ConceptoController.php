<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\EgresoDetalle;
use App\Models\HistorialAccion;
use App\Models\IngresoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ConceptoController extends Controller
{
    public $validacion = [
        "categoria_id" => "required",
        "nombre" => "required|min:2",
    ];

    public $mensajes = [
        "categoria_id.required" => "Este campo es obligatorio",
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
    ];

    public function index()
    {
        return Inertia::render("Conceptos/Index");
    }

    public function listado(Request $request)
    {
        $conceptos = Concepto::select("conceptos.*");

        if ($request->order && $request->order == "desc") {
            $conceptos->orderBy("conceptos.id", $request->order);
        }

        $conceptos = $conceptos->get();

        return response()->JSON([
            "conceptos" => $conceptos
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $conceptos = Concepto::with(["categoria"])->select("conceptos.*");
        if (trim($search) != "") {
            $conceptos->where("nombre", "LIKE", "%$search%");
        }

        $conceptos = $conceptos->paginate($request->itemsPerPage);
        return response()->JSON([
            "conceptos" => $conceptos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Concepto
            $nuevo_concepto = Concepto::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_concepto, "conceptos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' REGISTRO UN CONCEPTO',
                'datos_original' => $datos_original,
                'modulo' => 'CONCEPTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("conceptos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Concepto $concepto)
    {
    }

    public function update(Concepto $concepto, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($concepto, "conceptos");
            $concepto->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($concepto, "conceptos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' MODIFICÓ UN CONCEPTO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'CONCEPTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("conceptos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(Concepto $concepto)
    {
        DB::beginTransaction();
        try {
            $usos = IngresoDetalle::where("concepto_id", $concepto->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar esta categoría porque esta siendo utilizada por otros registros",
                ]);
            }
            $usos = EgresoDetalle::where("concepto_id", $concepto->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar esta categoría porque esta siendo utilizada por otros registros",
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($concepto, "conceptos");
            $concepto->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' ELIMINÓ UN CONCEPTO',
                'datos_original' => $datos_original,
                'modulo' => 'CONCEPTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);
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
