<?php

namespace App\Http\Controllers;

use App\Models\AlmacenProducto;
use App\Models\KardexProducto;
use App\Models\Producto;
use App\Models\SalidaProducto;
use App\Models\SucursalProducto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SalidaProductoController extends Controller
{
    public $validacion = [
        "producto_id" => "required",
        "proveedor_id" => "required",
        "precio" => "required",
        "cantidad" => "required",
        "tipo_salida_id" => "required",
        "lugar" => "required",
        "producto_barras" => "required|array|min:1"
    ];

    public $mensajes = [
        "producto_id.required" => "Este campo es obligatorio",
        "proveedor_id.required" => "Este campo es obligatorio",
        "precio.required" => "Este campo es obligatorio",
        "cantidad.required" => "Este campo es obligatorio",
        "tipo_salida_id.required" => "Este campo es obligatorio",
        "lugar.required" => "Este campo es obligatorio",
        "sucursal_id.required" => "Este campo es obligatorio",
        "producto_barras.required" => "Este campo es obligatorio",
        "producto_barras.min" => "Debes ingresar al menos :min productos",
    ];

    public function index()
    {
        return Inertia::render("SalidaProductos/Index");
    }

    public function listado()
    {
        $salida_productos = SalidaProducto::with(["producto", "tipo_salida", "producto_barras", "sucursal"])->select("salida_productos.*")->get();
        return response()->JSON([
            "salida_productos" => $salida_productos
        ]);
    }

    public function api(Request $request)
    {
        $usuarios = SalidaProducto::with(["producto", "tipo_salida", "producto_barras", "sucursal"])->select("salida_productos.*");
        $usuarios = $usuarios->get();
        return response()->JSON(["data" => $usuarios]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $usuarios = SalidaProducto::with(["producto", "tipo_salida", "producto_barras", "sucursal"])->select("salida_productos.*");

        if (trim($search) != "") {
            $usuarios->where("nombre", "LIKE", "%$search%");
        }

        $usuarios = $usuarios->paginate($request->itemsPerPage);
        return response()->JSON([
            "usuarios" => $usuarios
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $producto = Producto::find($request->producto_id);
            if ($producto->descontar_stock == 'SI') {
                // VALIDAR STOCK
                if ($request->lugar == 'ALMACEN') {
                    $stock_producto = AlmacenProducto::where("producto_id", $request->producto_id)->get()->first();
                } else {
                    $stock_producto = SucursalProducto::where("producto_id", $request->producto_id)->get()->first();
                }
                if (!$stock_producto) {
                    throw new Exception('No es posible realizar el registro debido a que el stock disponible es de 0');
                } elseif ($stock_producto->stock_actual < $request->cantidad) {
                    throw new Exception('No es posible realizar el registro debido a que la cantidad supera al stock actual ' . $stock_producto->stock_actual);
                }
            }
            // crear SalidaProducto
            $request["fecha_registro"] = date("Y-m-d");
            $nueva_salida_producto = SalidaProducto::create(array_map('mb_strtoupper', $request->all()));
            // registrar kardex
            KardexProducto::registroEgreso($nueva_salida_producto->lugar, "SALIDA", $nueva_salida_producto->id, $nueva_salida_producto->producto, $nueva_salida_producto->cantidad, $nueva_salida_producto->producto->precio, $nueva_salida_producto->descripcion);
            $datos_original = HistorialAccion::getDetalleRegistro($nueva_salida_producto, "salida_productos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UNA SALIDA DE PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'SALIDA DE PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return response()->JSON([
                'sw' => true,
                'salida_producto' => $nueva_salida_producto,
                'msj' => 'El registro se realizó de forma correcta',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, SalidaProducto $salida_producto)
    {
        $request->validate($this->validacion, $this->mensajes);

        $request->validate($this->validacion, $this->mensajes);
        if ($request->producto_id != $salida_producto->producto_id) {
            return response()->JSON([
                'sw' => false,
                'message' => "Error, los datos enviados son incorrectos",
            ], 500);
        } else {
            DB::beginTransaction();
            try {
                if ($salida_producto->producto->descontar_stock == 'SI') {
                    // incrementar el stock
                    Producto::incrementarStock($salida_producto->producto, $salida_producto->cantidad, $salida_producto->lugar);

                    // VALIDAR STOCK
                    if ($salida_producto->lugar == 'ALMACEN') {
                        $stock_producto = Almacen::where("producto_id", $request->producto_id)->get()->first();
                    } else {
                        $stock_producto = SucursalStock::where("producto_id", $request->producto_id)->get()->first();
                    }
                    if (!$stock_producto) {
                        throw new Exception('No es posible realizar el registro debido a que el stock disponible es de 0');
                    } elseif ($stock_producto->stock_actual < $request->cantidad) {
                        throw new Exception('No es posible realizar el registro debido a que la cantidad supera al stock disponible ' . $stock_producto->stock_actual);
                    }
                }

                $datos_original = HistorialAccion::getDetalleRegistro($salida_producto, "salida_productos");
                $salida_producto->update(array_map('mb_strtoupper', $request->all()));

                if ($salida_producto->producto->descontar_stock == 'SI') {
                    // DESCONTAR STOCK
                    Producto::decrementarStock($salida_producto->producto, $salida_producto->cantidad, $salida_producto->lugar);
                }

                // actualizar kardex
                $kardex = KardexProducto::where("lugar", $salida_producto->lugar)
                    ->where("producto_id", $salida_producto->producto_id)
                    ->where("tipo_registro", "SALIDA")
                    ->where("registro_id", $salida_producto->id)
                    ->get()->first();
                KardexProducto::actualizaRegistrosKardex($kardex->id, $kardex->producto_id, $salida_producto->lugar);

                $datos_nuevo = HistorialAccion::getDetalleRegistro($salida_producto, "salida_productos");
                HistorialAccion::create([
                    'user_id' => Auth::user()->id,
                    'accion' => 'MODIFICACIÓN',
                    'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UNA SALIDA DE PRODUCTO',
                    'datos_original' => $datos_original,
                    'datos_nuevo' => $datos_nuevo,
                    'modulo' => 'SALIDA DE PRODUCTOS',
                    'fecha' => date('Y-m-d'),
                    'hora' => date('H:i:s')
                ]);

                DB::commit();
                return response()->JSON([
                    'sw' => true,
                    'salida_producto' => $salida_producto,
                    'msj' => 'El registro se actualizó de forma correcta'
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                throw ValidationException::withMessages([
                    'error' =>  $e->getMessage(),
                ]);
            }
        }
    }

    public function show(SalidaProducto $salida_producto)
    {
        return response()->JSON([
            'sw' => true,
            'salida_producto' => $salida_producto
        ], 200);
    }

    public function destroy(SalidaProducto $salida_producto)
    {
        DB::beginTransaction();
        try {
            $eliminar_kardex = KardexProducto::where("lugar", $salida_producto->lugar)
                ->where("tipo_registro", "SALIDA")
                ->where("registro_id", $salida_producto->id)
                ->where("producto_id", $salida_producto->producto_id)
                ->get()
                ->first();
            $id_kardex = $eliminar_kardex->id;
            $id_producto = $eliminar_kardex->producto_id;
            $eliminar_kardex->delete();

            $anterior = KardexProducto::where("lugar", $salida_producto->lugar)
                ->where("producto_id", $id_producto)
                ->where("id", "<", $id_kardex)
                ->get()
                ->last();
            $actualiza_desde = null;
            if ($anterior) {
                $actualiza_desde = $anterior;
            } else {
                // comprobar si existen registros posteriorres al actualizado
                $siguiente = KardexProducto::where("lugar", $salida_producto->lugar)
                    ->where("producto_id", $id_producto)
                    ->where("id", ">", $id_kardex)
                    ->get()->first();
                if ($siguiente)
                    $actualiza_desde = $siguiente;
            }

            if ($actualiza_desde) {
                // actualizar a partir de este registro los sgtes. registros
                KardexProducto::actualizaRegistrosKardex($actualiza_desde->id, $actualiza_desde->producto_id, $salida_producto->lugar);
            }

            // incrementar el stock
            if ($salida_producto->producto->descontar_stock == 'SI') {
                Producto::incrementarStock($salida_producto->producto, $salida_producto->cantidad, $salida_producto->lugar);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($salida_producto, "salida_productos");
            $salida_producto->delete();

            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UNA SALIDA DE PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'SALIDA DE PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return response()->JSON([
                'sw' => true,
                'msj' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
