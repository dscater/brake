<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\EgresoDetalle;
use App\Models\HistorialAccion;
use App\Models\IngresoDetalle;
use App\Services\HistorialAccionService;
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


    private $modulo = "CONCEPTOS";

    public function __construct(private HistorialAccionService $historialAccionService) {}

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

    public function byCategoria(Request $request)
    {
        $categoria_id = $request->categoria_id;
        $conceptos = Concepto::where("categoria_id", $categoria_id)->get();
        return response()->JSON($conceptos);
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

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN CONCEPTO", $nuevo_concepto);

            DB::commit();
            return redirect()->route("conceptos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Concepto $concepto) {}

    public function update(Concepto $concepto, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $old_concepto = Concepto::find($concepto->id);
            $concepto->update(array_map('mb_strtoupper', $request->all()));
            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN CONCEPTO", $old_concepto, $concepto);

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

            $old_concepto = Concepto::find($concepto->id);
            $concepto->delete();

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UN CONCEPTO", $old_concepto);

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
