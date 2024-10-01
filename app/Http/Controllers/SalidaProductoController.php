<?php

namespace App\Http\Controllers;

use App\Models\AlmacenProducto;
use App\Models\HistorialAccion;
use App\Models\KardexProducto;
use App\Models\Producto;
use App\Models\ProductoBarra;
use App\Models\SalidaProducto;
use App\Models\SucursalProducto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class SalidaProductoController extends Controller
{
    public $validacion = [
        "producto_id" => "required",
        "fecha_salida" => "required",
        "tipo_salida_id" => "required",
    ];

    public $mensajes = [
        "producto_id.required" => "Este campo es obligatorio",
        "fecha_salida.required" => "Este campo es obligatorio",
        "tipo_salida_id.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("SalidaProductos/Index");
    }

    public function listado()
    {
        $salida_productos = SalidaProducto::with(["producto", "tipo_salida", "sucursal"])->select("salida_productos.*")->get();
        return response()->JSON([
            "salida_productos" => $salida_productos
        ]);
    }

    public function api(Request $request)
    {
        $usuarios = SalidaProducto::with(["producto", "tipo_salida", "sucursal"])->select("salida_productos.*");
        $usuarios = $usuarios->get();
        return response()->JSON(["data" => $usuarios]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $usuarios = SalidaProducto::with(["producto", "tipo_salida", "sucursal"])->select("salida_productos.*");

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
            $request["fecha_registro"] = date("Y-m-d");
            $producto_barra = ProductoBarra::find($request["producto_id"]["id"]);


            if ($producto_barra->venta_id || $producto_barra->venta_detalle_id) {
                throw new Exception("Ocurrió un error. El producto fue vendido");
            }

            if (!$producto_barra) {
                throw new Exception("Ocurrió un error. No se pudo encontrar el producto");
            }
            // VALIDAR STOCK
            if ($producto_barra->lugar == 'ALMACÉN') {
                $stock_producto = AlmacenProducto::where("producto_id", $producto_barra->producto_id)->get()->first();
            } else {
                $stock_producto = SucursalProducto::where("producto_id", $producto_barra->producto_id)
                    ->where("sucursal_id", $producto_barra->sucursal_id)
                    ->get()->first();
            }
            Log::debug($stock_producto);
            if (!$stock_producto) {
                throw new Exception('No es posible realizar el registro debido a que el stock disponible es de 0');
            } elseif ($stock_producto->stock_actual < $request->cantidad) {
                throw new Exception('No es posible realizar el registro debido a que la cantidad supera al stock actual ' . $stock_producto->stock_actual);
            }
            // crear SalidaProducto
            $data_salida =  [
                "producto_id" => $producto_barra->producto_id,
                "cantidad" => 1,
                "fecha_salida" => $request->fecha_salida,
                "tipo_salida_id" => $request->tipo_salida_id,
                "descripcion" => mb_strtoupper($request->descripcion),
                "lugar" => $producto_barra->lugar,
                "sucursal_id" => $producto_barra->sucursal_id,
                "fecha_registro" => $request->fecha_registro
            ];
            $nueva_salida_producto = SalidaProducto::create($data_salida);
            $producto_barra->salida_id = $nueva_salida_producto->id;
            $producto_barra->save();
            // registrar kardex
            KardexProducto::registroEgreso($nueva_salida_producto->lugar, "SALIDA", $nueva_salida_producto->id, $nueva_salida_producto->producto, $nueva_salida_producto->cantidad, $nueva_salida_producto->producto->precio, $nueva_salida_producto->descripcion, $nueva_salida_producto->sucursal_id);
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
            return redirect()->route("salida_productos.index")->with("bien", "Registro realizado");
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
                $datos_original = HistorialAccion::getDetalleRegistro($salida_producto, "salida_productos");
                $data_salida =  [
                    "fecha_salida" => $request->fecha_salida,
                    "tipo_salida_id" => $request->tipo_salida_id,
                    "descripcion" => mb_strtoupper($request->descripcion),
                ];
                $salida_producto->update($data_salida);
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
                return redirect()->route("salida_productos.index")->with("bien", "Registro actualizado");
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
            'salida_producto' => $salida_producto->load(["producto"])
        ], 200);
    }

    public function destroy(SalidaProducto $salida_producto)
    {
        DB::beginTransaction();
        try {
            $producto_barras = ProductoBarra::where("salida_id", $salida_producto->id)->get();
            foreach ($producto_barras as $producto_barra) {
                if ($producto_barra->venta_id || $producto_barra->venta_detalle_id) {
                    throw new Exception("No es posible eliminar este registro debido a que el producto registr");
                }
                $producto_barra->update(["salida_id", null]);
            }
            $eliminar_kardex = KardexProducto::where("lugar", $salida_producto->lugar)
                ->where("tipo_registro", "SALIDA")
                ->where("registro_id", $salida_producto->id)
                ->where("producto_id", $salida_producto->producto_id);
            if ($salida_producto->lugar == 'SUCURSAL') {
                $eliminar_kardex->where("sucursal_id", $salida_producto->sucursal_id);
            }
            $eliminar_kardex = $eliminar_kardex->get()
                ->first();
            $id_kardex = $eliminar_kardex->id;
            $id_producto = $eliminar_kardex->producto_id;
            $eliminar_kardex->delete();

            $anterior = KardexProducto::where("lugar", $salida_producto->lugar)
                ->where("producto_id", $id_producto)
                ->where("id", "<", $id_kardex);

            if ($salida_producto->lugar == 'SUCURSAL') {
                $anterior->where("sucursal_id", $salida_producto->sucursal_id);
            }
            $anterior = $anterior->get()
                ->last();
            $actualiza_desde = null;
            if ($anterior) {
                $actualiza_desde = $anterior;
            } else {
                // comprobar si existen registros posteriorres al actualizado
                $siguiente = KardexProducto::where("lugar", $salida_producto->lugar)
                    ->where("producto_id", $id_producto)
                    ->where("id", ">", $id_kardex);
                if ($salida_producto->lugar == 'SUCURSAL') {
                    $siguiente->where("sucursal_id", $salida_producto->sucursal_id);
                }
                $siguiente = $siguiente->get()->first();
                if ($siguiente)
                    $actualiza_desde = $siguiente;
            }

            if ($actualiza_desde) {
                // actualizar a partir de este registro los sgtes. registros
                KardexProducto::actualizaRegistrosKardex($actualiza_desde->id, $actualiza_desde->producto_id, $salida_producto->lugar, $salida_producto->sucursal_id);
            }

            // incrementar el stock
            if ($salida_producto->lugar == 'SUCURSAL') {
                Producto::incrementarStock($salida_producto->producto, $salida_producto->cantidad, $salida_producto->lugar, $salida_producto->sucursal_id);
            } else {
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
