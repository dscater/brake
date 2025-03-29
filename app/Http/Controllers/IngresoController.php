<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Ingreso;
use App\Models\IngresoDetalle;
use App\Services\HistorialAccionService;
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


    private $modulo = "INGRESOS ECONÓMICOS";

    public function __construct(private HistorialAccionService $historialAccionService) {}

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

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN INGRESO", $nuevo_ingreso, null, ["ingreso_detalles"]);

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
            $old_ingreso = clone $ingreso;
            $old_ingreso->load("ingreso_detalles");
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

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN INGRESO", $old_ingreso, $ingreso, ["ingreso_detalles"]);

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
            $old_ingreso = clone $ingreso;
            $old_ingreso->load("ingreso_detalles");
            
            $ingreso->ingreso_detalles()->delete();
            $ingreso->delete();

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UN INGRESO", $old_ingreso, null, ["ingreso_detalles"]);
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
