<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Ingreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class IngresoController extends Controller
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
        return Inertia::render("Ingresos/Index");
    }

    public function listado(Request $request)
    {
        $ingresos = Ingreso::select("ingresos.*");

        if ($request->order && $request->order == "desc") {
            $ingresos->orderBy("ingresos.id", $request->order);
        }

        $ingresos = $ingresos->get();

        return response()->JSON([
            "ingresos" => $ingresos
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $ingresos = Ingreso::with(["categoria"])->select("ingresos.*");
        if (trim($search) != "") {
            $ingresos->where("nombre", "LIKE", "%$search%");
        }

        $ingresos = $ingresos->paginate($request->itemsPerPage);
        return response()->JSON([
            "ingresos" => $ingresos
        ]);
    }

    public function create()
    {
        return Inertia::render("Ingresos/Create");
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Ingreso
            $nuevo_ingreso = Ingreso::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_ingreso, "ingresos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' REGISTRO UN INGRESO ECONÓMICO',
                'datos_original' => $datos_original,
                'modulo' => 'INGRESO ECONÓMICOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("ingresos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Ingreso $ingreso)
    {
    }
    public function edit(Ingreso $ingreso)
    {
        $ingreso = $ingreso->load(["categoria", "ingreso_detalles"]);
        return Inertia::render("Ingresos/Edit", compact("ingreso"));
    }

    public function update(Ingreso $ingreso, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($ingreso, "ingresos");
            $ingreso->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($ingreso, "ingresos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' MODIFICÓ UN INGRESO ECONÓMICO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'INGRESO ECONÓMICOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("ingresos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(Ingreso $ingreso)
    {
        DB::beginTransaction();
        try {
            $usos = IngresoDetalle::where("ingreso_id", $ingreso->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar esta categoría porque esta siendo utilizada por otros registros",
                ]);
            }
            $usos = EgresoDetalle::where("ingreso_id", $ingreso->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar esta categoría porque esta siendo utilizada por otros registros",
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($ingreso, "ingresos");
            $ingreso->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' ELIMINÓ UN INGRESO ECONÓMICO',
                'datos_original' => $datos_original,
                'modulo' => 'INGRESO ECONÓMICOS',
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
