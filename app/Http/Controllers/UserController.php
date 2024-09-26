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

            "proveedors.index",
            "proveedors.create",
            "proveedors.edit",
            "proveedors.destroy",

            "sucursals.index",
            "sucursals.create",
            "sucursals.edit",
            "sucursals.destroy",

            "categorias.index",
            "categorias.create",
            "categorias.edit",
            "categorias.destroy",

            "marcas.index",
            "marcas.create",
            "marcas.edit",
            "marcas.destroy",

            "unidad_medidas.index",
            "unidad_medidas.create",
            "unidad_medidas.edit",
            "unidad_medidas.destroy",

            "productos.index",
            "productos.create",
            "productos.edit",
            "productos.destroy",

            "tipo_ingresos.index",
            "tipo_ingresos.create",
            "tipo_ingresos.edit",
            "tipo_ingresos.destroy",

            "tipo_salidas.index",
            "tipo_salidas.create",
            "tipo_salidas.edit",
            "tipo_salidas.destroy",

            "ingreso_productos.index",
            "ingreso_productos.create",
            "ingreso_productos.edit",
            "ingreso_productos.destroy",

            "configuracions.index",
            "configuracions.create",
            "configuracions.edit",
            "configuracions.destroy",

            "reportes.usuarios",
        ],
        "SUPERVISOR DE SUCURSAL" => [],
        "OPERADOR" => [],
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

        return $array_infos;
    }
}
