<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\IngresoProductoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\NotificacionUserController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SalidaProductoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\TipoIngresoController;
use App\Http\Controllers\TipoSalidaController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
})->name("porta.index");

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
})->name("login");

Route::get("configuracions/getConfiguracion", [ConfiguracionController::class, 'getConfiguracion'])->name("configuracions.getConfiguracion");

Route::middleware('auth')->prefix("admin")->group(function () {
    // INICIO
    Route::get('/inicio', [InicioController::class, 'inicio'])->name('inicio');

    // CONFIGURACION
    Route::resource("configuracions", ConfiguracionController::class)->only(
        ["index", "show", "update"]
    );

    // USUARIO
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/update_foto', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get("getUser", [UserController::class, 'getUser'])->name('users.getUser');
    Route::get("permisos", [UserController::class, 'permisos']);

    // USUARIOS
    Route::put("usuarios/password/{user}", [UsuarioController::class, 'actualizaPassword'])->name("usuarios.password");
    Route::get("usuarios/api", [UsuarioController::class, 'api'])->name("usuarios.api");
    Route::get("usuarios/paginado", [UsuarioController::class, 'paginado'])->name("usuarios.paginado");
    Route::get("usuarios/listado", [UsuarioController::class, 'listado'])->name("usuarios.listado");
    Route::get("usuarios/listado/byTipo", [UsuarioController::class, 'byTipo'])->name("usuarios.byTipo");
    Route::get("usuarios/show/{user}", [UsuarioController::class, 'show'])->name("usuarios.show");
    Route::put("usuarios/update/{user}", [UsuarioController::class, 'update'])->name("usuarios.update");
    Route::delete("usuarios/{user}", [UsuarioController::class, 'destroy'])->name("usuarios.destroy");
    Route::resource("usuarios", UsuarioController::class)->only(
        ["index", "store"]
    );

    // SUCURSALS
    Route::put("sucursals/update_estado/{cliente}", [SucursalController::class, 'update_estado'])->name("sucursals.update_estado");
    Route::get("sucursals/api", [SucursalController::class, 'api'])->name("sucursals.api");
    Route::get("sucursals/paginado", [SucursalController::class, 'paginado'])->name("sucursals.paginado");
    Route::get("sucursals/listado", [SucursalController::class, 'listado'])->name("sucursals.listado");
    Route::resource("sucursals", SucursalController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // PROVEEDORES
    Route::put("proveedors/update_estado/{cliente}", [ProveedorController::class, 'update_estado'])->name("proveedors.update_estado");
    Route::get("proveedors/api", [ProveedorController::class, 'api'])->name("proveedors.api");
    Route::get("proveedors/paginado", [ProveedorController::class, 'paginado'])->name("proveedors.paginado");
    Route::get("proveedors/listado", [ProveedorController::class, 'listado'])->name("proveedors.listado");
    Route::resource("proveedors", ProveedorController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // CATEGORIAS
    Route::get("categorias/api", [CategoriaController::class, 'api'])->name("categorias.api");
    Route::get("categorias/paginado", [CategoriaController::class, 'paginado'])->name("categorias.paginado");
    Route::get("categorias/listado", [CategoriaController::class, 'listado'])->name("categorias.listado");
    Route::resource("categorias", CategoriaController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // MARCAS
    Route::get("marcas/api", [MarcaController::class, 'api'])->name("marcas.api");
    Route::get("marcas/paginado", [MarcaController::class, 'paginado'])->name("marcas.paginado");
    Route::get("marcas/listado", [MarcaController::class, 'listado'])->name("marcas.listado");
    Route::resource("marcas", MarcaController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // UNIDAD DE MEDIDA
    Route::get("unidad_medidas/api", [UnidadMedidaController::class, 'api'])->name("unidad_medidas.api");
    Route::get("unidad_medidas/paginado", [UnidadMedidaController::class, 'paginado'])->name("unidad_medidas.paginado");
    Route::get("unidad_medidas/listado", [UnidadMedidaController::class, 'listado'])->name("unidad_medidas.listado");
    Route::resource("unidad_medidas", UnidadMedidaController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // PRODUCTOS
    Route::get("productos/api", [ProductoController::class, 'api'])->name("productos.api");
    Route::get("productos/paginado", [ProductoController::class, 'paginado'])->name("productos.paginado");
    Route::get("productos/listado", [ProductoController::class, 'listado'])->name("productos.listado");
    Route::resource("productos", ProductoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // STOCK PRODUCTOS
    Route::get("productos/stock_productos/lista", [ProductoController::class, 'lista'])->name("stock_productos.lista");
    Route::get("productos/stock_productos", [ProductoController::class, 'stock_productos'])->name("stock_productos.index");

    // TIPO INGRESOS
    Route::get("tipo_ingresos/api", [TipoIngresoController::class, 'api'])->name("tipo_ingresos.api");
    Route::get("tipo_ingresos/paginado", [TipoIngresoController::class, 'paginado'])->name("tipo_ingresos.paginado");
    Route::get("tipo_ingresos/listado", [TipoIngresoController::class, 'listado'])->name("tipo_ingresos.listado");
    Route::resource("tipo_ingresos", TipoIngresoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // TIPO SALIDAS
    Route::get("tipo_salidas/api", [TipoSalidaController::class, 'api'])->name("tipo_salidas.api");
    Route::get("tipo_salidas/paginado", [TipoSalidaController::class, 'paginado'])->name("tipo_salidas.paginado");
    Route::get("tipo_salidas/listado", [TipoSalidaController::class, 'listado'])->name("tipo_salidas.listado");
    Route::resource("tipo_salidas", TipoSalidaController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // INGRESO PRODUCTOS
    Route::get("ingreso_productos/api", [IngresoProductoController::class, 'api'])->name("ingreso_productos.api");
    Route::get("ingreso_productos/paginado", [IngresoProductoController::class, 'paginado'])->name("ingreso_productos.paginado");
    Route::get("ingreso_productos/listado", [IngresoProductoController::class, 'listado'])->name("ingreso_productos.listado");
    Route::resource("ingreso_productos", IngresoProductoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // SALIDA PRODUCTOS
    Route::get("salida_productos/api", [SalidaProductoController::class, 'api'])->name("salida_productos.api");
    Route::get("salida_productos/paginado", [SalidaProductoController::class, 'paginado'])->name("salida_productos.paginado");
    Route::get("salida_productos/listado", [SalidaProductoController::class, 'listado'])->name("salida_productos.listado");
    Route::resource("salida_productos", SalidaProductoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // CLIENTES
    Route::get("clientes/api", [ClienteController::class, 'api'])->name("clientes.api");
    Route::get("clientes/paginado", [ClienteController::class, 'paginado'])->name("clientes.paginado");
    Route::get("clientes/listado", [ClienteController::class, 'listado'])->name("clientes.listado");
    Route::resource("clientes", ClienteController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // REPORTES
    Route::get('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");
});
require __DIR__ . '/auth.php';
