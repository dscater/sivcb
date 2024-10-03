<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Lote;
use App\Models\SucursalProducto;
use App\Models\User;
use App\Models\Venta;
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

            "salida_productos.index",
            "salida_productos.create",
            "salida_productos.edit",
            "salida_productos.destroy",

            "clientes.index",
            "clientes.create",
            "clientes.edit",
            "clientes.destroy",

            "ventas.index",
            "ventas.create",
            "ventas.edit",
            "ventas.destroy",

            "distribucion_productos.index",
            "distribucion_productos.create",
            "distribucion_productos.edit",
            "distribucion_productos.destroy",

            "configuracions.index",
            "configuracions.create",
            "configuracions.edit",
            "configuracions.destroy",

            "reportes.usuarios",
            "reportes.stock_productos",
            "reportes.kardex_productos",
            "reportes.ventas",
            "reportes.ingreso_productos",
            "reportes.salida_productos",
            "reportes.productos",
        ],
        "SUPERVISOR DE SUCURSAL" => [
            "proveedors.index",
            "proveedors.create",

            "categorias.index",
            "categorias.create",

            "marcas.index",
            "marcas.create",

            "unidad_medidas.index",
            "unidad_medidas.create",

            "productos.index",
            "productos.create",

            "ingreso_productos.index",
            "ingreso_productos.create",
            "ingreso_productos.edit",
            "ingreso_productos.destroy",

            "salida_productos.index",
            "salida_productos.create",
            "salida_productos.edit",
            "salida_productos.destroy",

            "clientes.index",
            "clientes.create",
            "clientes.edit",
            "clientes.destroy",

            "ventas.index",
            "ventas.create",
            "ventas.edit",
            "ventas.destroy",

            "reportes.stock_productos",
            "reportes.kardex_productos",
            "reportes.ventas",
            "reportes.ingreso_productos",
            "reportes.salida_productos",
            "reportes.productos",
        ],
        "OPERADOR" => [
            "clientes.index",
            "clientes.create",
            "clientes.edit",
            "clientes.destroy",

            "ventas.index",
            "ventas.create",
            "ventas.edit",

            "reportes.stock_productos",
            "reportes.ventas",
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
        if (in_array('ventas.index', self::$permisos[$tipo])) {
            if (Auth::user()->tipo == 'ADMINISTRADOR') {
                $ventas = Venta::count();
                $ventas_m = Venta::sum("total_final");
            } else {
                $ventas = Venta::where("sucursal_id", Auth::user()->sucursal_id)->count();
                $ventas_m = Venta::where("sucursal_id", Auth::user()->sucursal_id)->sum("total_final");
            }
            $array_infos[] = [
                'label' => 'VENTAS',
                'cantidad' => $ventas,
                'color' => 'bg-warning',
                'icon' => "fa-list",
                "url" => "ventas.index"
            ];

            $array_infos[] = [
                'label' => 'TOTAL VENTAS',
                'cantidad' => number_format($ventas_m, 2, ".", ","),
                'color' => 'bg-success',
                'icon' => "fa-money-bill",
                "url" => "ventas.index"
            ];
        }

        if (in_array('clientes.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'CLIENTES',
                'cantidad' => Cliente::count(),
                'color' => 'bg-info',
                'icon' => "fa-user-friends",
                "url" => "clientes.index"
            ];
        }

        if (Auth::user()->tipo != 'ADMINISTRADOR') {
            $producto_sucursal = SucursalProducto::where("sucursal_id",Auth::user()->sucursal_id)->sum("stock_actual");
            $array_infos[] = [
                'label' => 'PRODUCTOS',
                'cantidad' => $producto_sucursal,
                'color' => 'bg-teal',
                'icon' => "fa-boxes",
                "url" => "productos.index"
            ];
        }

        return $array_infos;
    }
}
