<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Ingreso;
use App\Models\IngresoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class IngresoController extends Controller
{
    public $validacion = [
        "categoria_id" => "required",
        "fecha" => "required|date",
    ];

    public $mensajes = [
        "categoria_id.required" => "Este campo es obligatorio",
        "fecha.required" => "Este campo es obligatorio",
        "fecha.date" => "Debes ingresar una fecha valida",
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
        $ingresos = Ingreso::with(["categoria", "ingreso_detalles.concepto"])->select("ingresos.*");
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
        $ingreso_detalles = $request->ingreso_detalles;
        if (!isset($request->ingreso_detalles) || count($ingreso_detalles) <= 0) {

            throw ValidationException::withMessages([
                'error' =>  "Debes agregar al menos 1 concepto",
            ]);
        }
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Ingreso
            $nuevo_ingreso = Ingreso::create([
                "fecha" => $request->fecha,
                "categoria_id" => $request->categoria_id,
                "fecha_registro" => $request->fecha_registro
            ]);

            foreach ($ingreso_detalles as $item) {
                $nuevo_ingreso->ingreso_detalles()->create([
                    "concepto_id" => $item["concepto_id"],
                    "descripcion" => mb_strtoupper($item["descripcion"]),
                    "cantidad" => $item["cantidad"],
                    "monto" => $item["monto"],
                ]);
            }

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
        $ingreso = $ingreso->load(["categoria", "ingreso_detalles.concepto"]);
        return Inertia::render("Ingresos/Show", compact("ingreso"));
    }
    public function edit(Ingreso $ingreso)
    {
        $ingreso = $ingreso->load(["categoria", "ingreso_detalles.concepto"]);
        return Inertia::render("Ingresos/Edit", compact("ingreso"));
    }

    public function update(Ingreso $ingreso, Request $request)
    {
        $ingreso_detalles = $request->ingreso_detalles;
        if (!isset($request->ingreso_detalles) || count($ingreso_detalles) <= 0) {

            throw ValidationException::withMessages([
                'error' =>  "Debes agregar al menos 1 concepto",
            ]);
        }
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($ingreso, "ingresos");
            $ingreso->update([
                "fecha" => $request->fecha,
                "categoria_id" => $request->categoria_id
            ]);

            if (isset($request->eliminados)) {
                foreach ($request->eliminados as $value) {
                    $ingreso_detalle = IngresoDetalle::find($value);
                    $ingreso_detalle->delete();
                }
            }

            foreach ($ingreso_detalles as $item) {
                if ($item["id"] == 0) {
                    $ingreso->ingreso_detalles()->create([
                        "concepto_id" => $item["concepto_id"],
                        "descripcion" => $item["descripcion"],
                        "cantidad" => $item["cantidad"],
                        "monto" => $item["monto"],
                    ]);
                }
            }

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
            $datos_original = HistorialAccion::getDetalleRegistro($ingreso, "ingresos");
            $ingreso->ingreso_detalles()->delete();
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
