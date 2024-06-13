<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Salida;
use App\Models\IngresoDetalle;
use App\Models\KardexProducto;
use App\Models\SalidaDetalle;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use PDF;

class ReporteController extends Controller
{
    public function usuarios()
    {
        return Inertia::render("Reportes/Usuarios");
    }

    public function r_usuarios(Request $request)
    {
        $tipo =  $request->tipo;
        $usuarios = User::where('id', '!=', 1)->orderBy("paterno", "ASC")->get();

        if ($tipo != 'TODOS') {
            $request->validate([
                'tipo' => 'required',
            ]);
            $usuarios = User::where('id', '!=', 1)->where('tipo', $request->tipo)->orderBy("paterno", "ASC")->get();
        }

        $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('usuarios.pdf');
    }

    public function productos()
    {
        return Inertia::render("Reportes/Productos");
    }

    public function r_productos(Request $request)
    {
        $filtro =  $request->filtro;
        $producto_id =  $request->producto_id;
        $categoria_id =  $request->categoria_id;
        $tipo_producto_id =  $request->tipo_producto_id;

        $productos = Producto::where('status',  1)->get();
        if ($filtro != 'todos') {
            if ($producto_id != 'todos' && $filtro == 'producto') {
                $productos = Producto::where('status',  1)->where("id", $producto_id)->get();
            }
            if ($categoria_id != 'todos' && $filtro == 'categoria') {
                $productos = Producto::where('status',  1)->where("categoria_id", $categoria_id)->get();
            }

            if ($tipo_producto_id != 'todos' && $filtro == 'tipo_producto') {
                $productos = Producto::where('status',  1)->where("tipo_producto_id", $tipo_producto_id)->get();
            }
        }


        $pdf = PDF::loadView('reportes.productos', compact('productos'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('productos.pdf');
    }

    public function ingreso_productos()
    {
        return Inertia::render("Reportes/IngresoProductos");
    }

    public function r_ingreso_productos(Request $request)
    {
        $filtro =  $request->filtro;
        $producto_id =  $request->producto_id;
        $categoria_id =  $request->categoria_id;
        $tipo_producto_id =  $request->tipo_producto_id;
        $tipo_ingreso_id =  $request->tipo_ingreso_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;


        $ingreso_productos = Ingreso::select("ingresos.*")
            ->join("ingreso_detalles", "ingreso_detalles.ingreso_id", "=", "ingresos.id")
            ->join("productos", "productos.id", "=", "ingreso_detalles.producto_id")
            ->where("productos.status", 1)
            ->get();

        if ($fecha_ini && $fecha_fin) {
            $ingreso_productos = Ingreso::whereBetween("fecha_ingreso", [$fecha_ini, $fecha_fin])->get();
        }

        if ($filtro != 'todos') {
            if ($filtro == 'producto' && $producto_id != 'todos') {
                $ingreso_productos = Ingreso::select("ingresos.*")
                    ->join("ingreso_detalles", "ingreso_detalles.ingreso_id", "=", "ingresos.id")
                    ->join("productos", "productos.id", "=", "ingreso_detalles.producto_id")
                    ->where("productos.status", 1)
                    ->where("productos.id", $producto_id);
                if ($fecha_ini && $fecha_fin) {
                    $ingreso_productos->whereBetween("ingresos.fecha_ingreso", [$fecha_ini, $fecha_fin]);
                }
                $ingreso_productos = $ingreso_productos->get();
            }
            if ($filtro == 'categoria' && $categoria_id != 'todos') {
                $ingreso_productos = Ingreso::select("ingresos.*")
                    ->join("ingreso_detalles", "ingreso_detalles.ingreso_id", "=", "ingresos.id")
                    ->join("productos", "productos.id", "=", "ingreso_detalles.producto_id")
                    ->where("productos.status", 1)
                    ->where("productos.categoria_id", $categoria_id);
                if ($fecha_ini && $fecha_fin) {
                    $ingreso_productos->whereBetween("ingresos.fecha_ingreso", [$fecha_ini, $fecha_fin]);
                }
                $ingreso_productos = $ingreso_productos->get();
            }
            if ($filtro == 'tipo_producto' && $tipo_producto_id != 'todos') {
                $ingreso_productos = Ingreso::select("ingresos.*")
                    ->join("ingreso_detalles", "ingreso_detalles.ingreso_id", "=", "ingresos.id")
                    ->join("productos", "productos.id", "=", "ingreso_detalles.producto_id")
                    ->where("productos.status", 1)
                    ->where("productos.tipo_producto_id", $tipo_producto_id);
                if ($fecha_ini && $fecha_fin) {
                    $ingreso_productos->whereBetween("ingresos.fecha_ingreso", [$fecha_ini, $fecha_fin]);
                }
                $ingreso_productos = $ingreso_productos->get();
            }
            if ($filtro == 'tipo_ingreso' && $tipo_ingreso_id != 'todos') {
                $ingreso_productos = Ingreso::select("ingresos.*")
                    ->join("ingreso_detalles", "ingreso_detalles.ingreso_id", "=", "ingresos.id")
                    ->join("productos", "productos.id", "=", "ingreso_detalles.producto_id")
                    ->where("productos.status", 1)
                    ->where("ingresos.tipo_ingreso_id", $tipo_ingreso_id);
                if ($fecha_ini && $fecha_fin) {
                    $ingreso_productos->whereBetween("ingresos.fecha_ingreso", [$fecha_ini, $fecha_fin]);
                }
                $ingreso_productos = $ingreso_productos->get();
            }
        }

        $pdf = PDF::loadView('reportes.ingreso_productos', compact('ingreso_productos'))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('ingreso_productos.pdf');
    }

    public function rg_ingreso_productos(Request $request)
    {
        $filtro =  $request->filtro;
        $producto_id =  $request->producto_id;
        $categoria_id =  $request->categoria_id;
        $tipo_producto_id =  $request->tipo_producto_id;
        $tipo_ingreso_id =  $request->tipo_ingreso_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $productos = Producto::where("status", 1)->get();
        if ($filtro != 'todos') {
            if ($filtro == 'producto' && $producto_id != 'todos') {
                $productos = Producto::where("status", 1)->where("id", $producto_id)->get();
            }
            if ($filtro == 'categoria' && $categoria_id != 'todos') {
                $productos = Producto::where("status", 1)->where("categoria_id", $categoria_id)->get();
            }
            if ($filtro == 'tipo_producto_id' && $tipo_producto_id != 'todos') {
                $productos = Producto::where("status", 1)->where("tipo_producto_id", $tipo_producto_id)->get();
            }
        }

        $data = [];
        foreach ($productos as $prod) {
            $total_ingresos = IngresoDetalle::join("ingresos", "ingresos.id", "=", "ingreso_detalles.ingreso_id");
            $total_ingresos->where("ingreso_detalles.producto_id", $prod->id);
            if ($filtro == 'tipo_ingreso' && $tipo_ingreso_id != 'todos') {
                $total_ingresos->where("ingresos.tipo_ingreso_id", $tipo_ingreso_id);
            }

            if ($fecha_ini && $fecha_fin) {
                $total_ingresos->whereBetween("ingresos.fecha_ingreso", [$fecha_ini, $fecha_fin]);
            }

            $total_ingresos = $total_ingresos->sum("ingreso_detalles.cantidad");
            $data[] = [$prod->nombre, (float)$total_ingresos];
        }

        return response()->JSON([
            "data" => $data,
        ]);
    }

    public function salida_productos()
    {
        return Inertia::render("Reportes/SalidaProductos");
    }

    public function r_salida_productos(Request $request)
    {
        $filtro =  $request->filtro;
        $producto_id =  $request->producto_id;
        $categoria_id =  $request->categoria_id;
        $tipo_producto_id =  $request->tipo_producto_id;
        $tipo_salida_id =  $request->tipo_salida_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $salida_productos = Salida::select("salidas.*")
            ->join("salida_detalles", "salida_detalles.salida_id", "=", "salidas.id")
            ->join("productos", "productos.id", "=", "salida_detalles.producto_id")
            ->where("productos.status", 1)
            ->get();

        if ($fecha_ini && $fecha_fin) {
            $salida_productos = Salida::whereBetween("fecha_salida", [$fecha_ini, $fecha_fin])->get();
        }

        if ($filtro != 'todos') {
            if ($filtro == 'producto' && $producto_id != 'todos') {
                $salida_productos = Salida::select("salidas.*")
                    ->join("salida_detalles", "salida_detalles.salida_id", "=", "salidas.id")
                    ->join("productos", "productos.id", "=", "salida_detalles.producto_id")
                    ->where("productos.status", 1)
                    ->where("productos.id", $producto_id);
                if ($fecha_ini && $fecha_fin) {
                    $salida_productos->whereBetween("salidas.fecha_salida", [$fecha_ini, $fecha_fin]);
                }
                $salida_productos = $salida_productos->get();
            }
            if ($filtro == 'categoria' && $categoria_id != 'todos') {
                $salida_productos = Salida::select("salidas.*")
                    ->join("salida_detalles", "salida_detalles.salida_id", "=", "salidas.id")
                    ->join("productos", "productos.id", "=", "salida_detalles.producto_id")
                    ->where("productos.status", 1)
                    ->where("productos.categoria_id", $categoria_id);
                if ($fecha_ini && $fecha_fin) {
                    $salida_productos->whereBetween("salidas.fecha_salida", [$fecha_ini, $fecha_fin]);
                }
                $salida_productos = $salida_productos->get();
            }
            if ($filtro == 'tipo_producto' && $tipo_producto_id != 'todos') {
                $salida_productos = Salida::select("salidas.*")
                    ->join("salida_detalles", "salida_detalles.salida_id", "=", "salidas.id")
                    ->join("productos", "productos.id", "=", "salida_detalles.producto_id")
                    ->where("productos.status", 1)
                    ->where("productos.tipo_producto_id", $tipo_producto_id);
                if ($fecha_ini && $fecha_fin) {
                    $salida_productos->whereBetween("salidas.fecha_salida", [$fecha_ini, $fecha_fin]);
                }
                $salida_productos = $salida_productos->get();
            }
            if ($filtro == 'tipo_salida' && $tipo_salida_id != 'todos') {
                $salida_productos = Salida::select("salidas.*")
                    ->join("salida_detalles", "salida_detalles.salida_id", "=", "salidas.id")
                    ->join("productos", "productos.id", "=", "salida_detalles.producto_id")
                    ->where("productos.status", 1)
                    ->where("salidas.tipo_salida_id", $tipo_salida_id);
                if ($fecha_ini && $fecha_fin) {
                    $salida_productos->whereBetween("salidas.fecha_salida", [$fecha_ini, $fecha_fin]);
                }
                $salida_productos = $salida_productos->get();
            }
        }

        $pdf = PDF::loadView('reportes.salida_productos', compact('salida_productos'))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('salida_productos.pdf');
    }

    public function rg_salida_productos(Request $request)
    {
        $filtro =  $request->filtro;
        $producto_id =  $request->producto_id;
        $categoria_id =  $request->categoria_id;
        $tipo_producto_id =  $request->tipo_producto_id;
        $tipo_salida_id =  $request->tipo_salida_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $productos = Producto::where("status", 1)->get();
        if ($filtro != 'todos') {
            if ($filtro == 'producto' && $producto_id != 'todos') {
                $productos = Producto::where("status", 1)->where("id", $producto_id)->get();
            }
            if ($filtro == 'categoria' && $categoria_id != 'todos') {
                $productos = Producto::where("status", 1)->where("categoria_id", $categoria_id)->get();
            }
            if ($filtro == 'tipo_producto_id' && $tipo_producto_id != 'todos') {
                $productos = Producto::where("status", 1)->where("tipo_producto_id", $tipo_producto_id)->get();
            }
        }

        $data = [];
        foreach ($productos as $prod) {
            $total_salidas = SalidaDetalle::join("salidas", "salidas.id", "=", "salida_detalles.salida_id");
            $total_salidas->where("salida_detalles.producto_id", $prod->id);
            if ($filtro == 'tipo_salida' && $tipo_salida_id != 'todos') {
                $total_salidas->where("salidas.tipo_salida_id", $tipo_salida_id);
            }

            if ($fecha_ini && $fecha_fin) {
                $total_salidas->whereBetween("salidas.fecha_salida", [$fecha_ini, $fecha_fin]);
            }

            $total_salidas = $total_salidas->sum("salida_detalles.cantidad");
            $data[] = [$prod->nombre, (float)$total_salidas];
        }

        return response()->JSON([
            "data" => $data,
        ]);
    }

    public function inventario_productos()
    {
        return Inertia::render("Reportes/InventarioProductos");
    }

    public function r_inventario_productos(Request $request)
    {
        $filtro =  $request->filtro;
        $producto_id =  $request->producto_id;
        $categoria_id =  $request->categoria_id;
        $tipo_producto_id =  $request->tipo_producto_id;

        $productos = Producto::where('status', 1)->get();
        if ($filtro != 'TODOS') {
            if ($filtro == 'producto' && $producto_id != 'todos') {
                $productos = Producto::where('status', 1)->where("id", $producto_id)->get();
            }
            if ($filtro == 'categoria' && $categoria_id != 'todos') {
                $productos = Producto::where('status', 1)->where("categoria_id", $categoria_id)->get();
            }
            if ($filtro == 'tipo_producto' && $tipo_producto_id != 'todos') {
                $productos = Producto::where('status', 1)->where("tipo_producto_id", $tipo_producto_id)->get();
            }
        }

        $pdf = PDF::loadView('reportes.inventario_productos', compact('productos'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('inventario_productos.pdf');
    }

    public function rg_inventario_productos(Request $request)
    {
        $filtro =  $request->filtro;
        $producto_id =  $request->producto_id;
        $categoria_id =  $request->categoria_id;
        $tipo_producto_id =  $request->tipo_producto_id;
        $data = [];

        $productos = Producto::where('status', 1)->get();
        if ($filtro != 'TODOS') {
            if ($filtro == 'producto' && $producto_id != 'todos') {
                $productos = Producto::where('status', 1)->where("id", $producto_id)->get();
            }
            if ($filtro == 'categoria' && $categoria_id != 'todos') {
                $productos = Producto::where('status', 1)->where("categoria_id", $categoria_id)->get();
            }
            if ($filtro == 'tipo_producto' && $tipo_producto_id != 'todos') {
                $productos = Producto::where('status', 1)->where("tipo_producto_id", $tipo_producto_id)->get();
            }
        }

        foreach ($productos as $producto) {
            $data[] = [$producto->nombre, (float)$producto->stock_actual];
        }

        return response()->JSON([
            "data" => $data,
        ]);
    }

    public function kardex_productos()
    {
        return Inertia::render("Reportes/KardexProductos");
    }

    public function r_kardex_productos(Request $request)
    {
        $filtro = $request->filtro;
        $producto_id = $request->producto_id;
        $categoria_id = $request->categoria_id;
        $tipo_producto_id = $request->tipo_producto_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        if ($request->filtro == 'Producto') {
            $request->validate([
                'producto_id' => 'required',
            ]);
        }

        $productos = Producto::all();
        if ($filtro != 'todos') {
            if ($filtro == 'Producto') {
                $productos = Producto::where("id", $producto_id)->get();
            }
        }

        $array_kardex = [];
        $array_saldo_anterior = [];
        foreach ($productos as $registro) {
            $array_saldo_anterior[$registro->id] = [
                'sw' => false,
                'saldo_anterior' => []
            ];
            $kardex = KardexProducto::where('producto_id', $registro->id)
                ->whereBetween('fecha', [$fecha_ini, $fecha_fin])->get();
            // buscar saldo anterior si existe
            $saldo_anterior = KardexProducto::where('producto_id', $registro->id)
                ->where('fecha', '<', $fecha_ini)
                ->orderBy('created_at', 'asc')->get()->last();
            if ($saldo_anterior) {
                $cantidad_saldo = $saldo_anterior->cantidad_saldo;
                $monto_saldo = $saldo_anterior->monto_saldo;
                $array_saldo_anterior[$registro->id] = [
                    'sw' => true,
                    'saldo_anterior' => [
                        'cantidad_saldo' => $cantidad_saldo,
                        'monto_saldo' => $monto_saldo,
                    ]
                ];
            }
            $array_kardex[$registro->id] = $kardex;
        }

        $pdf = PDF::loadView('reportes.kardex_productos', compact('productos', 'array_kardex', 'array_saldo_anterior'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));
        return $pdf->stream('kardex_productos.pdf');
    }

    public function analisis_almacen()
    {
        return Inertia::render("Reportes/AnalisisAlmacen");
    }
}
