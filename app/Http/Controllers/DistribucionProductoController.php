<?php

namespace App\Http\Controllers;

use App\Models\DistribucionProducto;
use App\Models\HistorialAccion;
use App\Models\KardexProducto;
use App\Models\Producto;
use App\Models\ProductoBarra;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class DistribucionProductoController extends Controller
{
    public $validacion = [
        "sucursal_id" => "required",
        "producto_barras" => "required|array|min:1"
    ];

    public $mensajes = [
        "sucursal_id.required" => "Este campo es obligatorio",
        "producto_barras.required" => "Este campo es obligatorio",
        "producto_barras.min" => "Debes ingresar al menos :min productos",
    ];

    public function index()
    {
        return Inertia::render("DistribucionProductos/Index");
    }

    public function listado()
    {
        $distribucion_productos = DistribucionProducto::with(["producto_barras", "sucursal"])->select("distribucion_productos.*")->get();
        return response()->JSON([
            "distribucion_productos" => $distribucion_productos
        ]);
    }

    public function api(Request $request)
    {
        $usuarios = DistribucionProducto::with(["producto_barras", "sucursal"])->select("distribucion_productos.*");
        $usuarios = $usuarios->get();
        return response()->JSON(["data" => $usuarios]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $usuarios = DistribucionProducto::with(["producto_barras", "sucursal"])->select("distribucion_productos.*");

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
            $request['fecha_registro'] = date('Y-m-d');
            // crear el DistribucionProducto
            $datos_distrubicion = [
                "sucursal_id" => $request->sucursal_id,
                "fecha_registro" => $request->fecha_registro,
            ];
            $nueva_distribucion = DistribucionProducto::create($datos_distrubicion);

            // registrar producto barras
            if (!isset($request->producto_barras) || !$request->producto_barras) {
                throw new Exception("Ocurrió un error al intentar registrar, intenté mas tarde por favor");
            }
            $producto_barras = $request->producto_barras;
            foreach ($producto_barras as $item) {
                $producto_barra = ProductoBarra::findOrFail($item["id"]);
                $producto_barra->distribucion_id = $nueva_distribucion->id;
                $producto_barra->lugar = "SUCURSAL";
                $producto_barra->sucursal_id = $nueva_distribucion->sucursal_id;
                $producto_barra->save();

                // registrar kardex EGRESO Almacén
                KardexProducto::registroEgreso("ALMACÉN", "DISTRIBUCIÓN", $producto_barra->id, $producto_barra->producto, 1, $producto_barra->producto->precio, "DISTRIBUCIÓN DE PRODUCTO");
                // registrar kardex INGRESO Sucursal
                KardexProducto::registroIngreso("SUCURSAL", "DISTRIBUCIÓN", $producto_barra->id, $producto_barra->producto, 1, $producto_barra->producto->precio, "INGRESO POR DISTRIBUCIÓN", $nueva_distribucion->sucursal_id);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($nueva_distribucion, "distribucion_productos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN DISTRIBUCIÓN DE PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'DISTRIBUCIÓN DE PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("distribucion_productos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(DistribucionProducto $distribucion_producto)
    {
        return response()->JSON($distribucion_producto->load(["sucursal", "producto_barras.producto"]));
    }

    public function update(DistribucionProducto $distribucion_producto, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            unset($distribucion_producto->sucursal);
            $datos_original = HistorialAccion::getDetalleRegistro($distribucion_producto, "distribucion_productos");

            // eliminados
            $eliminados = $request->eliminados;
            if (isset($request->eliminados) && $eliminados) {
                foreach ($eliminados as $item_e) {
                    $producto_barra = ProductoBarra::find($item_e);

                    // INCREMENTA KARDEX ALMACÉN
                    KardexProducto::registroIngreso("ALMACÉN", "DISTRIBUCIÓN", $producto_barra->id, $producto_barra->producto, 1, $producto_barra->producto->precio, "INGRESO POR ACTUALIZACIÓN DE DISTRIBUCIÓN");

                    // DECREMENTA KARDEX SUCURSAL
                    KardexProducto::registroEgreso("SUCURSAL", "DISTRIBUCIÓN", $producto_barra->id, $producto_barra->producto, 1, $producto_barra->producto->precio, "EGRESO POR ACTUALIZACIÓN DE DISTRIBUCIÓN", $producto_barra->sucursal_id);

                    $producto_barra->lugar = 'ALMACÉN';
                    $producto_barra->distribucion_id = null;
                    $producto_barra->sucursal_id = null;

                    if (!$producto_barra->salida_id && !$producto_barra->venta_id && !$producto_barra->venta_detalle_id) {
                        $producto_barra->save();
                    } else {
                        throw new Exception("No es posible actualizar el registro debido a que uno o mas registros del mismo fueron utilizados");
                    }
                }
            }

            if (!isset($request->producto_barras) || !$request->producto_barras) {
                throw new Exception("Ocurrió un error al intentar registrar, intenté mas tarde por favor");
            }

            $producto_barras = $request->producto_barras;
            foreach ($producto_barras as $item) {
                $producto_barra = ProductoBarra::findOrFail($item["id"]);
                if ($producto_barra->distribucion_id == null) {
                    $producto_barra->distribucion_id = $distribucion_producto->id;
                    $producto_barra->lugar = "SUCURSAL";
                    $producto_barra->sucursal_id = $distribucion_producto->sucursal_id;
                    $producto_barra->save();

                    // registrar kardex EGRESO Almacén
                    KardexProducto::registroEgreso("ALMACÉN", "DISTRIBUCIÓN", $producto_barra->id, $producto_barra->producto, 1, $producto_barra->producto->precio, "DISTRIBUCIÓN DE PRODUCTO");
                    // registrar kardex INGRESO Sucursal
                    KardexProducto::registroIngreso("SUCURSAL", "DISTRIBUCIÓN", $producto_barra->id, $producto_barra->producto, 1, $producto_barra->producto->precio, "INGRESO POR DISTRIBUCIÓN", $distribucion_producto->sucursal_id);
                }
            }
            $datos_nuevo = HistorialAccion::getDetalleRegistro($distribucion_producto, "distribucion_productos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN DISTRIBUCIÓN DE PRODUCTO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'DISTRIBUCIÓN DE PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("distribucion_productos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(DistribucionProducto $distribucion_producto)
    {
        DB::beginTransaction();
        try {

            foreach ($distribucion_producto->producto_barras as $producto_barra) {
                // INCREMENTA KARDEX ALMACÉN
                KardexProducto::registroIngreso("ALMACÉN", "DISTRIBUCIÓN", $producto_barra->id, $producto_barra->producto, 1, $producto_barra->producto->precio, "INGRESO POR ELIMINACIÓN DE DISTRIBUCIÓN");

                // DECREMENTA KARDEX SUCURSAL
                KardexProducto::registroEgreso("SUCURSAL", "DISTRIBUCIÓN", $producto_barra->id, $producto_barra->producto, 1, $producto_barra->producto->precio, "EGRESO POR ELIMINACIÓN DE DISTRIBUCIÓN", $producto_barra->sucursal_id);

                $producto_barra->lugar = 'ALMACÉN';
                $producto_barra->distribucion_id = null;
                $producto_barra->sucursal_id = null;

                if (!$producto_barra->salida_id && !$producto_barra->venta_id && !$producto_barra->venta_detalle_id) {
                    $producto_barra->save();
                } else {
                    throw new Exception("No es posible eliminar el registro debido a que uno o mas registros del mismo fueron utilizados");
                }
            }

            $datos_original = HistorialAccion::getDetalleRegistro($distribucion_producto, "distribucion_productos");
            $distribucion_producto->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN DISTRIBUCIÓN DE PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'DISTRIBUCIÓN DE PRODUCTOS',
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
