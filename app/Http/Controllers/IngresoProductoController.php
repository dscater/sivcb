<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\IngresoProducto;
use App\Models\KardexProducto;
use App\Models\Producto;
use App\Models\ProductoBarra;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class IngresoProductoController extends Controller
{
    public $validacion = [
        "producto_id" => "required",
        "proveedor_id" => "required",
        "precio" => "required",
        "cantidad" => "required",
        "tipo_ingreso_id" => "required",
        "lugar" => "required",
        "producto_barras" => "required|array|min:1"
    ];

    public $mensajes = [
        "producto_id.required" => "Este campo es obligatorio",
        "proveedor_id.required" => "Este campo es obligatorio",
        "precio.required" => "Este campo es obligatorio",
        "cantidad.required" => "Este campo es obligatorio",
        "tipo_ingreso_id.required" => "Este campo es obligatorio",
        "lugar.required" => "Este campo es obligatorio",
        "sucursal_id.required" => "Este campo es obligatorio",
        "producto_barras.required" => "Este campo es obligatorio",
        "producto_barras.min" => "Debes ingresar al menos :min productos",
    ];

    public function index()
    {
        return Inertia::render("IngresoProductos/Index");
    }

    public function listado()
    {
        $ingreso_productos = IngresoProducto::with(["producto", "proveedor", "tipo_ingreso", "producto_barras", "sucursal"])->select("ingreso_productos.*");

        if (Auth::user()->tipo != 'ADMINISTRADOR') {
            $ingreso_productos->where("sucursal_id", Auth::user()->sucursal_id);
            if (Auth::user()->tipo == 'SUPERVISOR DE SUCURSAL') {
                $ingreso_productos->orWhere("sucursal_id", nulL);
            }
        }

        $ingreso_productos = $ingreso_productos->get();
        return response()->JSON([
            "ingreso_productos" => $ingreso_productos
        ]);
    }

    public function api(Request $request)
    {
        $ingreso_productos = IngresoProducto::with(["producto", "proveedor", "tipo_ingreso", "producto_barras", "sucursal"])->select("ingreso_productos.*");
        if (Auth::user()->tipo != 'ADMINISTRADOR') {
            $ingreso_productos->where("sucursal_id", Auth::user()->sucursal_id);
            if (Auth::user()->tipo == 'SUPERVISOR DE SUCURSAL') {
                $ingreso_productos->orWhere("sucursal_id", nulL);
            }
        }
        $ingreso_productos = $ingreso_productos->get();
        return response()->JSON(["data" => $ingreso_productos]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $ingreso_productos = IngresoProducto::with(["producto", "proveedor", "tipo_ingreso", "producto_barras", "sucursal"])->select("ingreso_productos.*");

        if (Auth::user()->tipo != 'ADMINISTRADOR') {
            $ingreso_productos->where("sucursal_id", Auth::user()->sucursal_id);
            if (Auth::user()->tipo == 'SUPERVISOR DE SUCURSAL') {
                $ingreso_productos->orWhere("sucursal_id", nulL);
            }
        }

        if (trim($search) != "") {
            $ingreso_productos->where("nombre", "LIKE", "%$search%");
        }

        $ingreso_productos = $ingreso_productos->paginate($request->itemsPerPage);
        return response()->JSON([
            "ingreso_productos" => $ingreso_productos
        ]);
    }

    public function store(Request $request)
    {
        if ($request->lugar == 'SUCURSAL') {
            if (Auth::user()->tipo == 'ADMINISTRADOR') {
                $this->validacion["sucursal_id"] = "required";
            } else {
                $request["sucursal_id"] = Auth::user()->sucursal_id;
            }
        }
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $request['fecha_registro'] = date('Y-m-d');
            // crear el IngresoProducto
            $datos_ingreso = [
                "producto_id" => $request->producto_id,
                "proveedor_id" => $request->proveedor_id,
                "precio" => $request->precio,
                "cantidad" => $request->cantidad,
                "tipo_ingreso_id" => $request->tipo_ingreso_id,
                "descripcion" => mb_strtoupper($request->descripcion),
                "lugar" => mb_strtoupper($request->lugar),
                "sucursal_id" => $request->sucursal_id,
                "fecha_ingreso" => $request->fecha_registro,
                "fecha_registro" => $request->fecha_registro,
            ];

            if ($request->lugar == 'ALMACÉN') {
                unset($datos_ingreso["sucursal_id"]);
            }

            $nuevo_ingreso_producto = IngresoProducto::create($datos_ingreso);

            // registrar producto barras
            if (!isset($request->producto_barras) || !$request->producto_barras) {
                throw new Exception("Ocurrió un error al intentar registrar, intenté mas tarde por favor");
            }
            $producto_barras = $request->producto_barras;
            foreach ($producto_barras as $item) {
                $existe = ProductoBarra::where("codigo", $item["codigo"])->get()->first();
                if ($existe) {
                    throw new Exception("Uno o mas códigos de los productos agregados ya éxisten");
                }
                $data_barras = [
                    "producto_id" => $nuevo_ingreso_producto->producto_id,
                    "codigo" => $item["codigo"],
                    "lugar" => $nuevo_ingreso_producto->lugar,
                    "sucursal_id" => $nuevo_ingreso_producto->sucursal_id,
                    "ingreso_id" => $nuevo_ingreso_producto->id,
                ];

                if ($request->lugar == 'ALMACÉN') {
                    unset($data_barras["sucursal_id"]);
                }
                if ($item["id"] == 0) {
                    $nuevo_ingreso_producto->producto_barras()->create($data_barras);
                }
            }

            // registrar kardex
            if ($request->lugar == 'ALMACÉN') {
                KardexProducto::registroIngreso($nuevo_ingreso_producto->lugar, "INGRESO", $nuevo_ingreso_producto->id, $nuevo_ingreso_producto->producto, $nuevo_ingreso_producto->cantidad, $nuevo_ingreso_producto->producto->precio, $nuevo_ingreso_producto->descripcion);
            } else {
                KardexProducto::registroIngreso($nuevo_ingreso_producto->lugar, "INGRESO", $nuevo_ingreso_producto->id, $nuevo_ingreso_producto->producto, $nuevo_ingreso_producto->cantidad, $nuevo_ingreso_producto->producto->precio, $nuevo_ingreso_producto->descripcion, $request->sucursal_id);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_ingreso_producto, "ingreso_productos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN INGRESO DE PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'INGRESO DE PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("ingreso_productos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(IngresoProducto $ingreso_producto)
    {
        return response()->JSON($ingreso_producto->load(["producto", "proveedor", "tipo_ingreso", "producto_barras"]));
    }

    public function update(IngresoProducto $ingreso_producto, Request $request)
    {
        if ($request->lugar == 'SUCURSAL') {
            if (Auth::user()->tipo == 'ADMINISTRADOR') {
                $this->validacion["sucursal_id"] = "required";
            } else {
                $request["sucursal_id"] = Auth::user()->sucursal_id;
            }
        }
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            unset($ingreso_producto->producto);
            unset($ingreso_producto->sucursal);
            unset($ingreso_producto->proveedor);
            // descontar el stock
            if ($ingreso_producto->lugar == 'SUCURSAL') {
                Producto::decrementarStock($ingreso_producto->producto, $ingreso_producto->cantidad, $ingreso_producto->lugar, $ingreso_producto->sucursal_id);
            } else {
                Producto::decrementarStock($ingreso_producto->producto, $ingreso_producto->cantidad, $ingreso_producto->lugar);
            }
            $datos_original = HistorialAccion::getDetalleRegistro($ingreso_producto, "ingreso_productos");

            // actualizar ingreso
            $datos_ingreso = [
                "producto_id" => $request->producto_id,
                "proveedor_id" => $request->proveedor_id,
                "precio" => $request->precio,
                "cantidad" => $request->cantidad,
                "tipo_ingreso_id" => $request->tipo_ingreso_id,
                "descripcion" => mb_strtoupper($request->descripcion),
                "lugar" => mb_strtoupper($request->lugar),
                "sucursal_id" => $request->sucursal_id,
            ];

            $ingreso_producto->update($datos_ingreso);

            if ($ingreso_producto->lugar == 'ALMACÉN') {
                $ingreso_producto->sucursal_id = null;
            }
            $ingreso_producto->save();
            // eliminados
            $eliminados = $request->eliminados;
            if (isset($request->eliminados) && $eliminados) {
                foreach ($eliminados as $item_e) {
                    $producto_barra = ProductoBarra::find($item_e);
                    if (!$producto_barra->salida_id && !$producto_barra->venta_detalle_id && !$producto_barra->distribucion_id) {
                        $producto_barra->delete();
                    } else {
                        throw new Exception("No es posible eliminar el registro debido a que uno o mas registros del mismo fueron utilizados");
                    }
                }
            }

            if (!isset($request->producto_barras) || !$request->producto_barras) {
                throw new Exception("Ocurrió un error al intentar registrar, intenté mas tarde por favor");
            }

            $producto_barras = $request->producto_barras;
            foreach ($producto_barras as $item) {
                $data_barras = [
                    "producto_id" => $ingreso_producto->producto_id,
                    "codigo" => $item["codigo"],
                    "lugar" => $ingreso_producto->lugar,
                    "sucursal_id" => $ingreso_producto->sucursal_id,
                    "ingreso_id" => $ingreso_producto->id,
                ];

                if ($request->lugar == 'ALMACÉN') {
                    unset($data_barras["sucursal_id"]);
                }
                if ($item["id"] == 0) {
                    $ingreso_producto->producto_barras()->create($data_barras);
                } else {
                    $producto_barra = ProductoBarra::find($item["id"]);
                    $producto_barra->lugar = $ingreso_producto->lugar;
                    if ($request->lugar == 'ALMACÉN') {
                        $producto_barra->sucursal_id = null;
                    } else {
                        $producto_barra->sucursal_id = $ingreso_producto->sucursal_id;
                    }
                    $producto_barra->save();
                }
            }

            $kardex = KardexProducto::where("lugar", $ingreso_producto->lugar)
                ->where("producto_id", $ingreso_producto->producto_id)
                ->where("tipo_registro", "INGRESO")
                ->where("registro_id", $ingreso_producto->id);
            if ($request->lugar == 'SUCURSAL') {
                if (Auth::user()->tipo == 'ADMINISTRADOR') {
                    $kardex->where("sucursal_id", $request->sucursal_id);
                } else {
                    $request["sucursal_id"] = Auth::user()->sucursal_id;
                }
            }
            $kardex = $kardex->get()->first();
            $id_kardex = 0;
            if ($kardex) {
                $id_kardex = $kardex->id;
            }

            // incrementar stock
            if ($request->lugar == 'ALMACÉN') {
                Producto::incrementarStock($ingreso_producto->producto, $ingreso_producto->cantidad, $ingreso_producto->lugar);
                // actualizar kardex
                KardexProducto::actualizaRegistrosKardex($id_kardex, $ingreso_producto->producto_id, $ingreso_producto->lugar);
            } else {
                Producto::incrementarStock($ingreso_producto->producto, $ingreso_producto->cantidad, $ingreso_producto->lugar, $ingreso_producto->sucursal_id);
                KardexProducto::actualizaRegistrosKardex($id_kardex, $ingreso_producto->producto_id, $ingreso_producto->lugar, $request->sucursal_id);
            }

            $datos_nuevo = HistorialAccion::getDetalleRegistro($ingreso_producto, "ingreso_productos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN INGRESO DE PRODUCTO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'INGRESO DE PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("ingreso_productos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(IngresoProducto $ingreso_producto)
    {
        DB::beginTransaction();
        try {
            $eliminar_kardex = KardexProducto::where("lugar", $ingreso_producto->lugar)
                ->where("tipo_registro", "INGRESO")
                ->where("registro_id", $ingreso_producto->id)
                ->where("producto_id", $ingreso_producto->producto_id);

            if ($ingreso_producto->lugar == 'SUCURSAL') {
                $eliminar_kardex->where("sucursal_id", $ingreso_producto->sucursal_id);
            }
            $eliminar_kardex = $eliminar_kardex->get()->first();

            $id_kardex = $eliminar_kardex->id;
            $id_producto = $eliminar_kardex->producto_id;
            $eliminar_kardex->delete();

            $anterior = KardexProducto::where("lugar", $ingreso_producto->lugar)
                ->where("producto_id", $id_producto)
                ->where("id", "<", $id_kardex);
            if ($ingreso_producto->lugar == 'SUCURSAL') {
                $anterior->where("sucursal_id", $ingreso_producto->sucursal_id);
            }
            $anterior = $anterior->get()->last();
            $actualiza_desde = null;
            if ($anterior) {
                $actualiza_desde = $anterior;
            } else {
                // comprobar si existen registros posteriorres al actualizado
                $siguiente = KardexProducto::where("lugar", $ingreso_producto->lugar)
                    ->where("producto_id", $id_producto)
                    ->where("id", ">", $id_kardex);
                if ($ingreso_producto->lugar == 'SUCURSAL') {
                    $siguiente->where("sucursal_id", $ingreso_producto->sucursal_id);
                }
                $siguiente = $siguiente->get()->first();
                if ($siguiente)
                    $actualiza_desde = $siguiente;
            }

            if ($actualiza_desde) {
                // actualizar a partir de este registro los sgtes. registros
                if ($ingreso_producto->lugar == 'SUCURSAL') {
                    KardexProducto::actualizaRegistrosKardex($actualiza_desde->id, $actualiza_desde->producto_id, $ingreso_producto->lugar, $ingreso_producto->sucursal_id);
                } else {
                    KardexProducto::actualizaRegistrosKardex($actualiza_desde->id, $actualiza_desde->producto_id, $ingreso_producto->lugar);
                }
            }

            if ($ingreso_producto->lugar == 'SUCURSAL') {
                // descontar el stock
                Producto::decrementarStock($ingreso_producto->producto, $ingreso_producto->cantidad, $ingreso_producto->lugar, $ingreso_producto->sucursal_id);
            } else {
                // descontar el stock
                Producto::decrementarStock($ingreso_producto->producto, $ingreso_producto->cantidad, $ingreso_producto->lugar);
            }

            $producto_barras = $ingreso_producto->producto_barras;
            foreach ($producto_barras as $pb) {
                if (!$pb->salida_id && !$pb->venta_id && !$pb->venta_detalle_id && !$pb->distribucion_id) {
                    $pb->delete();
                } else {
                    throw new Exception("No es posible eliminar el registro debido a que uno o mas registros del mismo fueron utilizados");
                }
            }

            $datos_original = HistorialAccion::getDetalleRegistro($ingreso_producto, "ingreso_productos");
            $ingreso_producto->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN INGRESO DE PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'INGRESO DE PRODUCTOS',
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
