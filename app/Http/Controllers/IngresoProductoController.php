<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\IngresoProducto;
use App\Models\KardexProducto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class IngresoProductoController extends Controller
{
    public $validacion = [
        'producto_id' => 'required',
        'fecha_ingreso' => 'required',
        'cantidad' => 'required|numeric',
    ];

    public $mensajes = [
        "producto_id.required" => "Este campo es obligatorio",
        "fecha_ingreso.required" => "Este campo es obligatorio",
        "cantidad.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("IngresoProductos/Index");
    }

    public function listado(Request $request)
    {
        $ingreso_productos = IngresoProducto::with(["tipo_producto","producto"])->select("ingreso_productos.*");

        if (isset($request->byTipo) && $request->byTipo) {
            $ingreso_productos->where("tipo", $request->byTipo);
        }

        if ($request->order && $request->order == "desc") {
            $ingreso_productos->orderBy("ingreso_productos.id", $request->order);
        }

        $ingreso_productos = $ingreso_productos->get();

        return response()->JSON([
            "ingreso_productos" => $ingreso_productos
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $ingreso_productos = IngresoProducto::with(["tipo_producto","producto"])->select("ingreso_productos.*");
        if (trim($search) != "") {
            $ingreso_productos->where("nombre", "LIKE", "%$search%");
        }

        $ingreso_productos = $ingreso_productos->paginate($request->itemsPerPage);
        return response()->JSON([
            "ingreso_productos" => $ingreso_productos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el IngresoProducto
            $nuevo_ingreso_producto = IngresoProducto::create(array_map('mb_strtoupper', $request->all()));

            // registrar kardex
            KardexProducto::registroIngreso("INGRESO", $nuevo_ingreso_producto->id, $nuevo_ingreso_producto->producto, $nuevo_ingreso_producto->cantidad, $nuevo_ingreso_producto->producto->precio, $nuevo_ingreso_producto->descripcion);

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_ingreso_producto, "ingreso_productos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' REGISTRO UN INGRESO DE PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'INGRESO DE PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("ingreso_productos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(IngresoProducto $ingreso_producto) {}

    public function update(IngresoProducto $ingreso_producto, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            // descontar el stock
            Producto::decrementarStock($ingreso_producto->producto, $ingreso_producto->cantidad);

            $datos_original = HistorialAccion::getDetalleRegistro($ingreso_producto, "ingreso_productos");
            $ingreso_producto->update(array_map('mb_strtoupper', $request->all()));
            // INCREMENTAR STOCK
            Producto::incrementarStock($ingreso_producto->producto, $ingreso_producto->cantidad);

            // actualizar kardex
            $kardex = KardexProducto::where("producto_id", $ingreso_producto->producto_id)
                ->where("tipo_registro", "INGRESO")
                ->where("registro_id", $ingreso_producto->id)
                ->get()->first();
            KardexProducto::actualizaRegistrosKardex($kardex->id, $kardex->producto_id);

            $datos_nuevo = HistorialAccion::getDetalleRegistro($ingreso_producto, "ingreso_productos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' MODIFICÓ UN INGRESO DE PRODUCTO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'INGRESO DE PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("ingreso_productos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(IngresoProducto $ingreso_producto)
    {
        DB::beginTransaction();
        try {
            $eliminar_kardex = KardexProducto::where("tipo_registro", "INGRESO")
                ->where("registro_id", $ingreso_producto->id)
                ->where("producto_id", $ingreso_producto->producto_id)
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

            // descontar el stock
            Producto::decrementarStock($ingreso_producto->producto, $ingreso_producto->cantidad);
            $datos_original = HistorialAccion::getDetalleRegistro($ingreso_producto, "ingreso_productos");
            $ingreso_producto->delete();

            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' ELIMINÓ UN INGRESO DE PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'INGRESO DE PRODUCTOS',
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
