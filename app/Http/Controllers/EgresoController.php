<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use App\Models\HistorialAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class EgresoController extends Controller
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
        return Inertia::render("Egresos/Index");
    }

    public function listado(Request $request)
    {
        $egresos = Egreso::select("egresos.*");

        if ($request->order && $request->order == "desc") {
            $egresos->orderBy("egresos.id", $request->order);
        }

        $egresos = $egresos->get();

        return response()->JSON([
            "egresos" => $egresos
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $egresos = Egreso::with(["categoria", "egreso_detalles.concepto"])->select("egresos.*");
        if (trim($search) != "") {
            $egresos->where("nombre", "LIKE", "%$search%");
        }

        $egresos = $egresos->paginate($request->itemsPerPage);
        return response()->JSON([
            "egresos" => $egresos
        ]);
    }

    public function create()
    {
        return Inertia::render("Egresos/Create");
    }

    public function store(Request $request)
    {
        $egreso_detalles = $request->egreso_detalles;
        if (!isset($request->egreso_detalles) || count($egreso_detalles) <= 0) {

            throw ValidationException::withMessages([
                'error' =>  "Debes agregar al menos 1 concepto",
            ]);
        }
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Egreso
            $nuevo_egreso = Egreso::create([
                "fecha" => $request->fecha,
                "categoria_id" => $request->categoria_id,
                "fecha_registro" => $request->fecha_registro
            ]);

            foreach ($egreso_detalles as $item) {
                $nuevo_egreso->egreso_detalles()->create([
                    "concepto_id" => $item["concepto_id"],
                    "descripcion" => mb_strtoupper($item["descripcion"]),
                    "cantidad" => $item["cantidad"],
                    "monto" => $item["monto"],
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_egreso, "egresos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' REGISTRO UN EGRESO ECONÓMICO',
                'datos_original' => $datos_original,
                'modulo' => 'EGRESO ECONÓMICOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("egresos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Egreso $egreso)
    {
        $egreso = $egreso->load(["categoria", "egreso_detalles.concepto"]);
        return Inertia::render("Egresos/Show", compact("egreso"));
    }
    public function edit(Egreso $egreso)
    {
        $egreso = $egreso->load(["categoria", "egreso_detalles.concepto"]);
        return Inertia::render("Egresos/Edit", compact("egreso"));
    }

    public function update(Egreso $egreso, Request $request)
    {
        $egreso_detalles = $request->egreso_detalles;
        if (!isset($request->egreso_detalles) || count($egreso_detalles) <= 0) {

            throw ValidationException::withMessages([
                'error' =>  "Debes agregar al menos 1 concepto",
            ]);
        }
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($egreso, "egresos");
            $egreso->update([
                "fecha" => $request->fecha,
                "categoria_id" => $request->categoria_id,
            ]);

            if (isset($request->eliminados)) {
                foreach ($request->eliminados as $value) {
                    $egreso_detalle = EgresoDetalle::find($value);
                    $egreso_detalle->delete();
                }
            }

            foreach ($egreso_detalles as $item) {
                if ($item["id"] == 0) {
                    $egreso->egreso_detalles()->create([
                        "concepto_id" => $item["concepto_id"],
                        "descripcion" => $item["descripcion"],
                        "cantidad" => $item["cantidad"],
                        "monto" => $item["monto"],
                    ]);
                }
            }

            $datos_nuevo = HistorialAccion::getDetalleRegistro($egreso, "egresos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' MODIFICÓ UN EGRESO ECONÓMICO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'EGRESO ECONÓMICOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("egresos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(Egreso $egreso)
    {
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($egreso, "egresos");
            $egreso->egreso_detalles()->delete();
            $egreso->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' ELIMINÓ UN EGRESO ECONÓMICO',
                'datos_original' => $datos_original,
                'modulo' => 'EGRESO ECONÓMICOS',
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
