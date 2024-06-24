<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Egreso;
use App\Models\EgresoDetalle;
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

    public function ingresos()
    {
        return Inertia::render("Reportes/Ingresos");
    }
    public function r_ingresos(Request $request)
    {
        $categoria_id = $request->categoria_id;
        $concepto_id = $request->concepto_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;
        $ingresos = Ingreso::orderBy("created_at", "asc")->get();

        $categorias = Categoria::where("tipo", "INGRESO")->orderBy("nombre", "asc")->get();
        if ($categoria_id != 'todos') {
            $categorias = Categoria::where("tipo", "INGRESO")->where("id", $categoria_id)->orderBy("nombre", "asc")->get();
        }

        $html = "";
        $total_cantidad = 0;
        $total_monto = 0;
        foreach ($categorias as $item) {
            $html .= '<tr>';
            $html .= '<td colspan="5" class="subtitle">' . $item->nombre . '</td>';
            $html .= '</tr>';
            // DATOS
            $ingreso_detalles = IngresoDetalle::select("ingreso_detalles.*")
                ->join("ingresos", "ingresos.id", "=", "ingreso_detalles.ingreso_id")
                ->where("ingresos.categoria_id", $item->id)->orderBy("ingresos.fecha", "asc");
            if ($concepto_id != 'todos') {
                $ingreso_detalles->where("ingreso_detalles.concepto_id", $concepto_id);
            }

            if ($fecha_ini && $fecha_fin) {
                $ingreso_detalles->whereBetween("ingresos.fecha", [$fecha_ini, $fecha_fin]);
            }

            $ingreso_detalles = $ingreso_detalles->get();
            foreach ($ingreso_detalles as $value) {
                $html .= '<tr>';
                $html .= '<td>' . $value->concepto->nombre . '</td>';
                $html .= '<td>' . $value->descripcion . '</td>';
                $html .= '<td>' . $value->ingreso->fecha_t . '</td>';
                $html .= '<td class="centreado">' . $value->cantidad . '</td>';
                $html .= '<td class="centreado">' . $value->monto . '</td>';
                $html .= '</tr>';
                $total_cantidad += (float)$value->cantidad;
                $total_monto += (float)$value->monto;
            }
        }
        $html .= '<tr>';
        $html .= '<td colspan="3" class="derecha bold">TOTAL</td>';
        $html .= '<td class="centreado bold">' . $total_cantidad . '</td>';
        $html .= '<td class="centreado bold">' . $total_monto . '</td>';
        $html .= '</tr>';

        $pdf = PDF::loadView('reportes.ingresos', compact('ingresos', 'html'))->setPaper('legal', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('ingresos.pdf');
    }
    public function egresos()
    {
        return Inertia::render("Reportes/Egresos");
    }
    public function r_egresos(Request $request)
    {
        $categoria_id = $request->categoria_id;
        $concepto_id = $request->concepto_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;
        $egresos = Egreso::orderBy("created_at", "asc")->get();

        $categorias = Categoria::where("tipo", "EGRESO")->orderBy("nombre", "asc")->get();
        if ($categoria_id != 'todos') {
            $categorias = Categoria::where("tipo", "EGRESO")->where("id", $categoria_id)->orderBy("nombre", "asc")->get();
        }

        $html = "";
        $total_cantidad = 0;
        $total_monto = 0;
        foreach ($categorias as $item) {
            $html .= '<tr>';
            $html .= '<td colspan="5" class="subtitle">' . $item->nombre . '</td>';
            $html .= '</tr>';
            // DATOS
            $egreso_detalles = EgresoDetalle::select("egreso_detalles.*")
                ->join("egresos", "egresos.id", "=", "egreso_detalles.egreso_id")
                ->where("egresos.categoria_id", $item->id)->orderBy("egresos.fecha", "asc");
            if ($concepto_id != 'todos') {
                $egreso_detalles->where("egreso_detalles.concepto_id", $concepto_id);
            }

            if ($fecha_ini && $fecha_fin) {
                $egreso_detalles->whereBetween("egresos.fecha", [$fecha_ini, $fecha_fin]);
            }

            $egreso_detalles = $egreso_detalles->get();
            foreach ($egreso_detalles as $value) {
                $html .= '<tr>';
                $html .= '<td>' . $value->concepto->nombre . '</td>';
                $html .= '<td>' . $value->descripcion . '</td>';
                $html .= '<td>' . $value->egreso->fecha_t . '</td>';
                $html .= '<td class="centreado">' . $value->cantidad . '</td>';
                $html .= '<td class="centreado">' . $value->monto . '</td>';
                $html .= '</tr>';
                $total_cantidad += (float)$value->cantidad;
                $total_monto += (float)$value->monto;
            }
        }
        $html .= '<tr>';
        $html .= '<td colspan="3" class="derecha bold">TOTAL</td>';
        $html .= '<td class="centreado bold">' . $total_cantidad . '</td>';
        $html .= '<td class="centreado bold">' . $total_monto . '</td>';
        $html .= '</tr>';

        $pdf = PDF::loadView('reportes.egresos', compact('egresos', 'html'))->setPaper('legal', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('egresos.pdf');
    }
    public function presupuestos()
    {
        return Inertia::render("Reportes/Usuarios");
    }
    public function r_presupuestos(Request $request)
    {
    }
    public function ganancias()
    {
        return Inertia::render("Reportes/Usuarios");
    }
    public function r_ganancias(Request $request)
    {
    }
    public function movimientos()
    {
        return Inertia::render("Reportes/Usuarios");
    }
    public function r_movimientos(Request $request)
    {
    }
    public function rg_movimientos()
    {
    }
}
