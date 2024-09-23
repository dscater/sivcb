<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\User;
use App\Models\VentaLote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public static $permisos = [
        "ADMINISTRADOR" => [
            "usuarios.index",
            "usuarios.create",
            "usuarios.edit",
            "usuarios.destroy",

            "urbanizacions.index",
            "urbanizacions.create",
            "urbanizacions.edit",
            "urbanizacions.destroy",

            "manzanos.index",
            "manzanos.create",
            "manzanos.edit",
            "manzanos.destroy",

            "lotes.index",
            "lotes.create",
            "lotes.edit",
            "lotes.destroy",

            "planilla_cuotas.index",
            "planilla_cuotas.create",
            "planilla_cuotas.edit",
            "planilla_cuotas.destroy",

            "clientes.index",
            "clientes.create",
            "clientes.edit",
            "clientes.destroy",

            "venta_lotes.index",
            "venta_lotes.create",
            "venta_lotes.edit",
            "venta_lotes.destroy",

            "pagos.index",
            "pagos.create",
            "pagos.edit",
            "pagos.destroy",

            "notificacion_users.index",
            "notificacion_users.show",

            "configuracions.index",
            "configuracions.create",
            "configuracions.edit",
            "configuracions.destroy",

            "reportes.usuarios",
            "reportes.lotes_terrenos",
            "reportes.clientes",
            "reportes.planilla_pagos",
            "reportes.g_lotes_terrenos",
            "reportes.g_venta_lotes",
        ],
        "SUPERVISOR" => [
            "urbanizacions.index",
            "urbanizacions.create",
            "urbanizacions.edit",
            "urbanizacions.destroy",

            "manzanos.index",
            "manzanos.create",
            "manzanos.edit",
            "manzanos.destroy",

            "lotes.index",
            "lotes.create",
            "lotes.edit",
            "lotes.destroy",

            "planilla_cuotas.index",
            "planilla_cuotas.create",
            "planilla_cuotas.edit",
            "planilla_cuotas.destroy",

            "clientes.index",
            "clientes.create",
            "clientes.edit",
            "clientes.destroy",

            "venta_lotes.index",
            "venta_lotes.create",
            "venta_lotes.edit",
            "venta_lotes.destroy",

            "pagos.index",
            "pagos.create",
            "pagos.edit",
            "pagos.destroy",

            "notificacion_users.index",
            "notificacion_users.show",

            "reportes.lotes_terrenos",
            "reportes.clientes",
            "reportes.planilla_pagos",
            "reportes.g_lotes_terrenos",
            "reportes.g_venta_lotes",
        ],
        "CLIENTE" => [
            "venta_lotes.index",

            "notificacion_users.index",
            "notificacion_users.show",
        ],
        "AGENTE INMOBILIARIO" => [
            "clientes.index",
            "clientes.create",
            "clientes.edit",
            "clientes.destroy",

            "venta_lotes.index",
            "venta_lotes.create",
            "venta_lotes.edit",
            "venta_lotes.destroy",

            "pagos.index",
            "pagos.create",
            "pagos.edit",
            "pagos.destroy",

            "notificacion_users.index",
            "notificacion_users.show",

            "reportes.lotes_terrenos",
            "reportes.clientes",
            "reportes.planilla_pagos",
            "reportes.g_lotes_terrenos",
            "reportes.g_venta_lotes",
        ],
    ];

    public static function getPermisosUser()
    {
        $array_permisos = self::$permisos;
        if ($array_permisos[Auth::user()->tipo]) {
            return $array_permisos[Auth::user()->tipo];
        }
        return [];
    }


    public static function verificaPermiso($permiso)
    {
        if (in_array($permiso, self::$permisos[Auth::user()->tipo])) {
            return true;
        }
        return false;
    }

    public function permisos(Request $request)
    {
        return response()->JSON([
            "permisos" => $this->permisos[Auth::user()->tipo]
        ]);
    }

    public function getUser()
    {
        return response()->JSON([
            "user" => Auth::user()
        ]);
    }

    public static function getInfoBoxUser()
    {
        $tipo = Auth::user()->tipo;
        $array_infos = [];

        if (in_array('usuarios.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'USUARIOS',
                'cantidad' => User::where('id', '!=', 1)->where("tipo", "!=", "CLIENTE")->count(),
                'color' => 'bg-blue',
                'icon' => "fa-users",
                "url" => "usuarios.index"
            ];
        }

        if (in_array('clientes.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'CLIENTES',
                'cantidad' => User::where("tipo", "CLIENTE")->count(),
                'color' => 'bg-info',
                'icon' => "fa-user-friends",
                "url" => "clientes.index"
            ];
        }

        if (in_array('venta_lotes.index', self::$permisos[$tipo])) {
            $venta_lotes = VentaLote::count();
            if (Auth::user()->tipo == 'CLIENTE') {
                $venta_lotes = VentaLote::where("cliente_id", Auth::user()->cliente->id)->count();
            }
            $array_infos[] = [
                'label' => 'VENTA DE LOTES',
                'cantidad' => $venta_lotes,
                'color' => 'bg-orange',
                'icon' => "fa-clipboard-list",
                "url" => "venta_lotes.index"
            ];
        }

        if (in_array('lotes.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'LOTES',
                'cantidad' => Lote::count(),
                'color' => 'bg-red',
                'icon' => "fa-clipboard-list",
                "url" => "lotes.index"
            ];
        }

        return $array_infos;
    }
}
