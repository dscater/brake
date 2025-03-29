<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Producto;
use App\Models\TipoProducto;
use App\Services\HistorialAccionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class TipoProductoController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:2",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
    ];

    private $modulo = "TIPO DE PRODUCTOS";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function index()
    {
        return Inertia::render("TipoProductos/Index");
    }

    public function listado(Request $request)
    {
        $tipo_productos = TipoProducto::select("tipo_productos.*");

        if (isset($request->byTipo) && $request->byTipo) {
            $tipo_productos->where("tipo", $request->byTipo);
        }

        if ($request->order && $request->order == "desc") {
            $tipo_productos->orderBy("tipo_productos.id", $request->order);
        }

        $tipo_productos = $tipo_productos->get();

        return response()->JSON([
            "tipo_productos" => $tipo_productos
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $tipo_productos = TipoProducto::select("tipo_productos.*");
        if (trim($search) != "") {
            $tipo_productos->where("nombre", "LIKE", "%$search%");
        }

        $tipo_productos = $tipo_productos->paginate($request->itemsPerPage);
        return response()->JSON([
            "tipo_productos" => $tipo_productos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el TipoProducto
            $nuevo_tipo_producto = TipoProducto::create(array_map('mb_strtoupper', $request->all()));

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN TIPO DE PRODUCTO", $nuevo_tipo_producto);

            DB::commit();
            return redirect()->route("tipo_productos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(TipoProducto $tipo_producto) {}

    public function update(TipoProducto $tipo_producto, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $old_tipo_producto = clone $tipo_producto;

            $datos_original = HistorialAccion::getDetalleRegistro($tipo_producto, "tipo_productos");
            $tipo_producto->update(array_map('mb_strtoupper', $request->all()));

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN TIPO DE PRODUCTO", $old_tipo_producto, $tipo_producto);

            DB::commit();
            return redirect()->route("tipo_productos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(TipoProducto $tipo_producto)
    {
        DB::beginTransaction();
        try {
            $old_tipo_producto = clone $tipo_producto;
            $usos = Producto::where("tipo_producto_id", $tipo_producto->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar esta categoría porque esta siendo utilizada por otros registros",
                ]);
            }

            $tipo_producto->delete();
            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UN TIPO DE PRODUCTO", $old_tipo_producto);
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
