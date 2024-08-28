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

class ProductoController extends Controller
{
    public $validacion = [
        "tipo_producto_id" => "required",
        "nombre" => "required|min:2",
        "unidad" => "required|min:2",
        "precio" => "required|min:0",
    ];

    public $mensajes = [
        "tipo_producto_id.required" => "Este campo es obligatorio",
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
        "unidad.required" => "Este campo es obligatorio",
        "unidad.min" => "Debes ingresar al menos :min caracteres",
        "precio.required" => "Este campo es obligatorio",
        "precio.min" => "Debes ingresar al menos :min caracteres",
    ];

    public function index()
    {
        return Inertia::render("Productos/Index");
    }

    public function listado(Request $request)
    {
        $productos = Producto::with(["tipo_producto"])->select("productos.*");

        if (isset($request->byTipo) && $request->byTipo) {
            $productos->where("tipo", $request->byTipo);
        }

        if ($request->order && $request->order == "desc") {
            $productos->orderBy("productos.id", $request->order);
        }

        $productos = $productos->get();

        return response()->JSON([
            "productos" => $productos
        ]);
    }

    public function byTipoProducto(Request $request)
    {
        $productos = Producto::with(["tipo_producto"])->select("productos.*");
        $productos->where("tipo_producto_id", $request->tipo_producto_id);
        if ($request->order && $request->order == "desc") {
            $productos->orderBy("productos.id", $request->order);
        }
        $productos = $productos->get();
        return response()->JSON([
            "productos" => $productos
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $productos = Producto::with(["tipo_producto"])->select("productos.*");
        if (trim($search) != "") {
            $productos->where("nombre", "LIKE", "%$search%");
        }

        $productos = $productos->paginate($request->itemsPerPage);
        return response()->JSON([
            "productos" => $productos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Producto
            $nuevo_producto = Producto::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_producto, "productos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' REGISTRO UN PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("productos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Producto $producto) {}

    public function update(Producto $producto, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($producto, "productos");
            $producto->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($producto, "productos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' MODIFICÓ UN PRODUCTO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("productos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(Producto $producto)
    {
        DB::beginTransaction();
        try {
            $usos = IngresoProducto::where("producto_id", $producto->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar esta categoría porque esta siendo utilizada por otros registros",
                ]);
            }
            $usos = KardexProducto::where("producto_id", $producto->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar esta categoría porque esta siendo utilizada por otros registros",
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($producto, "productos");
            $producto->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->user . ' ELIMINÓ UN PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'PRODUCTOS',
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
