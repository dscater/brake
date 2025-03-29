<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\KardexProducto;
use App\Models\Producto;
use App\Models\SalidaProducto;
use App\Services\HistorialAccionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class SalidaProductoController extends Controller
{
    public $validacion = [
        'producto_id' => 'required',
        'fecha_salida' => 'required',
        'cantidad' => 'required|numeric',
    ];

    public $mensajes = [
        "producto_id.required" => "Este campo es obligatorio",
        "fecha_salida.required" => "Este campo es obligatorio",
        "cantidad.required" => "Este campo es obligatorio",
    ];

    private $modulo = "SALIDA DE PRODUCTOS";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function index()
    {
        return Inertia::render("SalidaProductos/Index");
    }

    public function listado(Request $request)
    {
        $salida_productos = SalidaProducto::with(["tipo_producto", "producto"])->select("salida_productos.*");

        if (isset($request->byTipo) && $request->byTipo) {
            $salida_productos->where("tipo", $request->byTipo);
        }

        if ($request->order && $request->order == "desc") {
            $salida_productos->orderBy("salida_productos.id", $request->order);
        }

        $salida_productos = $salida_productos->get();

        return response()->JSON([
            "salida_productos" => $salida_productos
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $salida_productos = SalidaProducto::with(["tipo_producto", "producto"])->select("salida_productos.*");
        if (trim($search) != "") {
            $salida_productos->where("nombre", "LIKE", "%$search%");
        }

        $salida_productos = $salida_productos->paginate($request->itemsPerPage);
        return response()->JSON([
            "salida_productos" => $salida_productos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            $producto = Producto::find($request->producto_id);
            // VALIDAR STOCK
            if ($producto->stock_actual < $request->cantidad) {
                throw ValidationException::withMessages([
                    'error' =>  'No es posible realizar el registro debido a que la cantidad supera al stock actual ' . $producto->stock_actual,
                ]);
            }
            // crear SalidaProducto
            $request["fecha_registro"] = date("Y-m-d");
            $nueva_salida_producto = SalidaProducto::create(array_map('mb_strtoupper', $request->all()));

            // registrar kardex
            KardexProducto::registroEgreso("SALIDA", $nueva_salida_producto->id, $nueva_salida_producto->producto, $nueva_salida_producto->cantidad, $nueva_salida_producto->producto->precio, $nueva_salida_producto->descripcion);

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA SALIDA DE PRODUCTO", $nueva_salida_producto);

            DB::commit();
            return redirect()->route("salida_productos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(SalidaProducto $salida_producto) {}

    public function update(SalidaProducto $salida_producto, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $old_salida_producto = clone $salida_producto;
            // incrementar el stock
            Producto::incrementarStock($salida_producto->producto, $salida_producto->cantidad);
            // VALIDAR STOCK
            $stock_actual_producto = (float)$salida_producto->producto->stock_actual;
            if ($stock_actual_producto < $request->cantidad) {
                Producto::decrementarStock($salida_producto->producto, $salida_producto->cantidad);
                throw ValidationException::withMessages([
                    'error' =>  'No es posible realizar el registro debido a que la cantidad supera al stock disponible ' . $stock_actual_producto,
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($salida_producto, "salida_productos");
            $salida_producto->update(array_map('mb_strtoupper', $request->all()));

            // DESCONTAR STOCK
            Producto::decrementarStock($salida_producto->producto, $salida_producto->cantidad);

            // actualizar kardex
            $kardex = KardexProducto::where("producto_id", $salida_producto->producto_id)
                ->where("tipo_registro", "SALIDA")
                ->where("registro_id", $salida_producto->id)
                ->get()->first();
            KardexProducto::actualizaRegistrosKardex($kardex->id, $kardex->producto_id);

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA SALIDA DE PRODUCTO", $old_salida_producto, $salida_producto);

            DB::commit();
            return redirect()->route("salida_productos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(SalidaProducto $salida_producto)
    {
        DB::beginTransaction();
        try {
            $old_salida_producto = clone $salida_producto;
            $eliminar_kardex = KardexProducto::where("tipo_registro", "SALIDA")
                ->where("registro_id", $salida_producto->id)
                ->where("producto_id", $salida_producto->producto_id)
                ->get()
                ->first();
            $id_kardex = $eliminar_kardex->id;
            $id_producto = $eliminar_kardex->producto_id;
            $eliminar_kardex->delete();

            $anterior = KardexProducto::where("producto_id", $id_producto)
                ->where("id", "<", $id_kardex)
                ->get()
                ->last();
            $actualiza_desde = null;
            if ($anterior) {
                $actualiza_desde = $anterior;
            } else {
                // comprobar si existen registros posteriorres al actualizado
                $siguiente = KardexProducto::where("producto_id", $id_producto)
                    ->where("id", ">", $id_kardex)
                    ->get()->first();
                if ($siguiente)
                    $actualiza_desde = $siguiente;
            }

            if ($actualiza_desde) {
                // actualizar a partir de este registro los sgtes. registros
                KardexProducto::actualizaRegistrosKardex($actualiza_desde->id, $actualiza_desde->producto_id);
            }

            // incrementar el stock
            Producto::incrementarStock($salida_producto->producto, $salida_producto->cantidad);

            $salida_producto->delete();

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA SALIDA DE PRODUCTO", $old_salida_producto);

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
