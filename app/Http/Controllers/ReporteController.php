<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Lote;
use App\Models\Urbanizacion;
use App\Models\User;
use App\Models\VentaLote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $usuarios = User::where('id', '!=', 1)->where("tipo", "!=", "CLIENTE")->orderBy("paterno", "ASC")->get();

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

    public function lotes_terrenos()
    {
        return Inertia::render("Reportes/LotesTerrenos");
    }

    public function r_lotes_terrenos(Request $request)
    {
        $urbanizacion_id = $request->urbanizacion_id;
        $manzano_id = $request->manzano_id;
        $ocupado = $request->ocupado;

        $urbanizacions = Urbanizacion::select("urbanizacions.*");
        if ($urbanizacion_id != 'todos') {
            $urbanizacions->where("id", $urbanizacion_id);
        }
        $urbanizacions = $urbanizacions->get();

        $pdf = PDF::loadView('reportes.lotes_terrenos', compact('urbanizacions', 'manzano_id', 'ocupado'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('lotes_terrenos.pdf');
    }

    public function clientes()
    {
        return Inertia::render("Reportes/Clientes");
    }

    public function r_clientes(Request $request)
    {
        $estado_cliente =  $request->estado_cliente;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $clientes = Cliente::select("clientes.*")
            ->join("users", "users.id", "=", "clientes.user_id");

        if ($estado_cliente != 'todos') {
            $clientes->where("clientes.estado_cliente", $estado_cliente);
        }

        if ($fecha_ini && $fecha_fin) {
            $clientes->whereBetween("users.fecha_registro", [$fecha_ini, $fecha_fin]);
        }

        $clientes = $clientes->get();


        $pdf = PDF::loadView('reportes.clientes', compact('clientes'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('clientes.pdf');
    }

    public function planilla_pagos()
    {
        return Inertia::render("Reportes/PlanillaPagos");
    }

    public function r_planilla_pagos(Request $request)
    {
        $cliente_id =  $request->cliente_id;

        $clientes = Cliente::select("clientes.*");

        if ($cliente_id != 'todos') {
            $clientes->where("id", $cliente_id);
        }

        $clientes = $clientes->get();

        $pdf = PDF::loadView('reportes.planilla_pagos', compact('clientes'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('planilla_pagos.pdf');
    }

    public function planilla_venta(Request $request)
    {
        $venta_lote_id =  $request->venta_lote_id;
        $venta_lote = VentaLote::find($venta_lote_id);
        $clientes = Cliente::select("clientes.*");
        $clientes->where("id", $venta_lote->cliente_id);
        $clientes = $clientes->get();

        $pdf = PDF::loadView('reportes.planilla_pagos', compact('clientes'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('planilla_pagos.pdf');
    }


    public function g_lotes_terrenos()
    {
        return Inertia::render("Reportes/GLotesTerrenos");
    }

    public function r_g_lotes_terrenos(Request $request)
    {
        $urbanizacion_id =  $request->urbanizacion_id;

        $urbanizacions = Urbanizacion::select("urbanizacions.*");

        if ($urbanizacion_id != 'todos') {
            $urbanizacions->where("id", $urbanizacion_id);
        }

        $urbanizacions = $urbanizacions->get();
        $categories = [];
        $series = [
            [
                "name" => "Disponibles",
                "data" => [],
                "color" => "#06bb7f",
            ],
            [
                "name" => "Ocupados",
                "data" => [],
                "color" => "#e44a36",
            ]
        ];
        foreach ($urbanizacions as $item) {
            $categories[] = $item->nombre;

            $disponibles = Lote::where("urbanizacion_id", $item->id)->where("vendido", 0)->count();
            $ocupados = Lote::where("urbanizacion_id", $item->id)->where("vendido", 1)->count();
            $series[0]["data"][] = $disponibles;
            $series[1]["data"][] = $ocupados;
        }

        return response()->JSON([
            "categories" => $categories,
            "series" => $series,
        ]);
    }

    public function r_pdf_lotes_terrenos(Request $request)
    {
        $urbanizacion_id =  $request->urbanizacion_id;

        $urbanizacions = Urbanizacion::select("urbanizacions.*");

        if ($urbanizacion_id != 'todos') {
            $urbanizacions->where("id", $urbanizacion_id);
        }

        $urbanizacions = $urbanizacions->get();

        $pdf = PDF::loadView('reportes.pdf_lotes_terrenos', compact('urbanizacions'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('lotes_terrenos.pdf');
    }

    public function g_venta_lotes()
    {
        return Inertia::render("Reportes/GVentaLotes");
    }

    public function r_g_venta_lotes(Request $request)
    {
        $urbanizacion_id =  $request->urbanizacion_id;

        $urbanizacions = Urbanizacion::select("urbanizacions.*");

        if ($urbanizacion_id != 'todos') {
            $urbanizacions->where("id", $urbanizacion_id);
        }

        $urbanizacions = $urbanizacions->get();
        $categories = [];
        $series = [
            [
                "name" => "Total",
                "data" => [],
                "color" => "#06bb7f",
            ],
        ];
        foreach ($urbanizacions as $item) {
            $categories[] = $item->nombre;
            $total = VentaLote::where("urbanizacion_id", $item->id)->sum("total_venta");
            $series[0]["data"][] = (float)$total;
        }

        return response()->JSON([
            "categories" => $categories,
            "series" => $series,
        ]);
    }

    public function r_pdf_venta_lotes(Request $request)
    {
        $urbanizacion_id =  $request->urbanizacion_id;

        $urbanizacions = Urbanizacion::select("urbanizacions.*");

        if ($urbanizacion_id != 'todos') {
            $urbanizacions->where("id", $urbanizacion_id);
        }

        $urbanizacions = $urbanizacions->get();

        $pdf = PDF::loadView('reportes.venta_lotes', compact('urbanizacions'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('venta_lotes.pdf');
    }
}
