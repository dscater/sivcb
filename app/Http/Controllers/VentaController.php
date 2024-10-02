<?php

namespace App\Http\Controllers;

use App\library\numero_a_letras\src\NumeroALetras;
use App\Models\Cliente;
use App\Models\HistorialAccion;
use App\Models\KardexProducto;
use App\Models\Producto;
use App\Models\ProductoBarra;
use App\Models\SucursalProducto;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class VentaController extends Controller
{
    public $validacion = [
        "cliente_id" => "required",
        "total" => "required",
        "descuento" => "required",
        "total_final" => "required",
    ];

    public $mensajes = [
        "cliente_id.required" => "Este campo es obligatorio",
        "sucursal_id.required" => "Este campo es obligatorio",
        "total.required" => "Este campo es obligatorio",
        "descuento.required" => "Este campo es obligatorio",
        "total_final.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("Ventas/Index");
    }

    public function listado()
    {
        $ventas = Venta::with(["venta_detalles", "producto_barras", "sucursal", "user", "cliente"])->select("ventas.*");

        if (Auth::user()->tipo != 'ADMINISTRADOR') {
            $ventas->where("sucursal_id", Auth::user()->sucursal_id);
        }
        $ventas = $ventas->get();
        return response()->JSON([
            "ventas" => $ventas
        ]);
    }

    public function api(Request $request)
    {
        $ventas = Venta::with(["venta_detalles", "producto_barras", "sucursal", "user", "cliente"])->select("ventas.*");
        if (Auth::user()->tipo != 'ADMINISTRADOR') {
            $ventas->where("sucursal_id", Auth::user()->sucursal_id);
        }
        $ventas = $ventas->get();
        return response()->JSON(["data" => $ventas]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $ventas = Venta::with(["venta_detalles", "producto_barras", "sucursal", "user", "cliente"])->select("ventas.*");
        if (Auth::user()->tipo != 'ADMINISTRADOR') {
            $ventas->where("sucursal_id", Auth::user()->sucursal_id);
        }
        if (trim($search) != "") {
            $ventas->where("nombre", "LIKE", "%$search%");
        }

        $ventas = $ventas->paginate($request->itemsPerPage);
        return response()->JSON([
            "ventas" => $ventas
        ]);
    }

    public function create()
    {
        return Inertia::render("Ventas/Create");
    }

    public function store(Request $request)
    {
        if (Auth::user()->tipo == 'ADMINISTRADOR') {
            $this->validacion["sucursal_id"] = "required";
        } else {
            $request["sucursal_id"] = Auth::user()->sucursal_id;
        }
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $request['fecha_registro'] = date('Y-m-d');
            // crear el Venta
            $cliente = Cliente::find($request->cliente_id);
            $datos_venta = [
                "user_id" => Auth::user()->id,
                "sucursal_id" => $request->sucursal_id,
                "cliente_id" => $request->cliente_id,
                "nit" => $cliente->ci,
                "total" => $request->total,
                "descuento" => $request->descuento,
                "total_final" => $request->total_final,
                "fecha_registro" => $request->fecha_registro,
            ];

            $nueva_venta = Venta::create($datos_venta);

            // registrar producto barras
            if (!isset($request->producto_barras) || !$request->producto_barras || !isset($request->venta_detalles) || !$request->venta_detalles) {
                throw new Exception("Ocurrió un error al intentar registrar, intenté mas tarde por favor");
            }

            $producto_barras = $request->producto_barras;
            $array_update = [];
            foreach ($producto_barras as $item) {
                $existe = ProductoBarra::where("codigo", $item["codigo"])->get()->first();
                if (!$existe) {
                    throw new Exception("Ocurrió un error al intentar registrar la venta, por favor intente nuevamente");
                }
                $existe->update([
                    "venta_id" => $nueva_venta->id,
                ]);
                $array_update[$item["producto_id"]][] = $item;
            }

            $venta_detalles = $request->venta_detalles;
            foreach ($venta_detalles as $vd) {
                $p_desc = round($nueva_venta->descuento / 100, 2);
                $sub_desc = (float)$vd["subtotal"] * (1 - $p_desc);
                $sub_desc = round($sub_desc, 2);
                $nuevo_detalle = $nueva_venta->venta_detalles()->create([
                    "producto_id" => $vd["producto_id"],
                    "cantidad" => $vd["cantidad"],
                    "precio" => $vd["precio"],
                    "subtotal" => $vd["subtotal"],
                    "descuento" => $nueva_venta->descuento,
                    "subtotaltotal" => $sub_desc,
                ]);

                foreach ($array_update[$vd["producto_id"]] as $value) {
                    $producto_barra = ProductoBarra::find($value["id"]);
                    $producto_barra->update(["venta_detalle_id" => $nuevo_detalle->id]);
                }
                // registrar kardex
                KardexProducto::registroEgreso("SUCURSAL", "VENTA", $nuevo_detalle->id, $nuevo_detalle->producto, $nuevo_detalle->cantidad, $nuevo_detalle->producto->precio, "VENTA DE PRODUCTO", $nueva_venta->sucursal_id);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($nueva_venta, "ventas");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UNA VENTA',
                'datos_original' => $datos_original,
                'modulo' => 'VENTAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("ventas.index")->with("bien", "Registro realizado")
                ->with("venta_id", $nueva_venta->id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Venta $venta)
    {
        return response()->JSON($venta->load(["venta_detalles.producto", "producto_barras", "sucursal", "user", "cliente"]));
    }

    public function imprimir(Venta $venta, Request $request)
    {
        $convertir = new NumeroALetras();
        $array_monto = explode('.', $venta->total_final);
        $literal = $convertir->convertir($array_monto[0]);
        $literal .= " " . $array_monto[1] . "/100." . " BOLIVIANOS";

        $nro_factura = (int)$venta->id;
        if ($nro_factura < 10) {
            $nro_factura = '000' . $nro_factura;
        } else if ($nro_factura < 100) {
            $nro_factura = '00' . $nro_factura;
        } else if ($nro_factura < 1000) {
            $nro_factura = '0' . $nro_factura;
        }

        $imprime = null;
        if (isset($request->imprime)) {
            $imprime = $request->imprime;
        }

        $customPaper = array(0, 0, 360, 600);
        $venta = $venta->load(["venta_detalles.producto", "venta_detalles.producto_barras", "sucursal", "user", "cliente"]);
        return Inertia::render("Ventas/Imprimir", compact("venta", "nro_factura", "literal", "imprime"));
    }

    public function edit(Venta $venta)
    {
        $venta = $venta->load(["venta_detalles.producto", "producto_barras", "sucursal", "user", "cliente"]);
        return Inertia::render("Ventas/Edit", compact("venta"));
    }

    public function update(Venta $venta, Request $request)
    {
        if (Auth::user()->tipo == 'ADMINISTRADOR') {
            $this->validacion["sucursal_id"] = "required";
        }
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            unset($venta->cliente);
            unset($venta->sucursal);
            $datos_original = HistorialAccion::getDetalleRegistro($venta, "ventas");

            // actualizar venta
            $cliente = Cliente::find($request->cliente_id);
            $datos_venta = [
                "sucursal_id" => $request->sucursal_id,
                "cliente_id" => $request->cliente_id,
                "nit" => $cliente->ci,
                "total" => $request->total,
                "descuento" => $request->descuento,
                "total_final" => $request->total_final,
            ];
            $venta->update($datos_venta);

            // registrar producto barras
            if (!isset($request->producto_barras) || !$request->producto_barras || !isset($request->venta_detalles) || !$request->venta_detalles) {
                throw new Exception("Ocurrió un error al intentar registrar, intenté mas tarde por favor");
            }

            // poner en null los registros de barras
            $venta->producto_barras()->update(["venta_id" => null, "venta_detalle_id" => null]);

            // eliminados
            $eliminados = $request->eliminados;
            if ($eliminados) {
                foreach ($eliminados as $value) {
                    $venta_detalle = VentaDetalle::find($value);
                    $producto = Producto::find($venta_detalle->producto_id);
                    // ACTUALIZAR KARDEX
                    $eliminar_kardex = KardexProducto::where("lugar", "SUCURSAL")
                        ->where("tipo_registro", "VENTA")
                        ->where("registro_id", $venta_detalle->id)
                        ->where("sucursal_id", $venta->sucursal_id)
                        ->where("producto_id", $venta_detalle->producto_id)
                        ->get()
                        ->first();
                    $id_kardex = $eliminar_kardex->id;
                    $id_producto = $eliminar_kardex->producto_id;
                    $eliminar_kardex->delete();

                    $anterior = KardexProducto::where("lugar", "SUCURSAL")
                        ->where("sucursal_id", $venta->sucursal_id)
                        ->where("producto_id", $id_producto)
                        ->where("id", "<", $id_kardex)
                        ->get()
                        ->last();
                    $actualiza_desde = null;
                    if ($anterior) {
                        $actualiza_desde = $anterior;
                    } else {
                        // comprobar si existen registros posteriorres al actualizado
                        $siguiente = KardexProducto::where("lugar", "SUCURSAL")
                            ->where("sucursal_id", $venta->sucursal_id)
                            ->where("producto_id", $id_producto)
                            ->where("id", ">", $id_kardex)
                            ->get()->first();
                        if ($siguiente)
                            $actualiza_desde = $siguiente;
                    }

                    if ($actualiza_desde) {
                        // actualizar a partir de este registro los sgtes. registros
                        KardexProducto::actualizaRegistrosKardex($actualiza_desde->id, $actualiza_desde->producto_id, "SUCURSAL", $venta->sucursal_id);
                    }

                    // incrementar el stock
                    Producto::incrementarStock($producto, $venta_detalle->cantidad, "SUCURSAL", $venta->sucursal_id);

                    $venta_detalle->delete();
                }
            }


            // registrar producto barras
            if (!isset($request->producto_barras) || !$request->producto_barras || !isset($request->venta_detalles) || !$request->venta_detalles) {
                throw new Exception("Ocurrió un error al intentar registrar, intenté mas tarde por favor");
            }
            $producto_barras = $request->producto_barras;
            $array_update = [];
            foreach ($producto_barras as $item) {
                $existe = ProductoBarra::where("codigo", $item["codigo"])->get()->first();
                if (!$existe) {
                    throw new Exception("Ocurrió un error al intentar registrar la venta, por favor intente nuevamente");
                }
                $existe->update([
                    "venta_id" => $venta->id,
                ]);
                $array_update[$item["producto_id"]][] = $item;
            }

            // venta detalles
            $venta_detalles = $request->venta_detalles;

            foreach ($venta_detalles as $vd) {
                $p_desc = round($venta->descuento / 100, 2);
                $sub_desc = (float)$vd["subtotal"] * (1 - $p_desc);
                $sub_desc = round($sub_desc, 2);
                if ($vd["id"] != 0) {
                    $venta_detalle = VentaDetalle::find($vd["id"]);

                    // incrementar el stock
                    $producto = Producto::find($vd["producto_id"]);
                    Producto::incrementarStock($producto, $vd["cantidad"], "SUCURSAL", $venta->sucursal_id);

                    // VALIDAR STOCK
                    $sucursal_stock = SucursalProducto::where("producto_id", $vd["producto_id"])
                        ->where("sucursal_id", $venta->sucursal_id)
                        ->get()->first();
                    if ($sucursal_stock->stock_actual < $vd["cantidad"]) {
                        throw new Exception('No es posible realizar el registro debido a que la cantidad supera al stock disponible ' . $sucursal_stock->stock_actual);
                    }

                    $venta_detalle = VentaDetalle::find($vd["id"]);
                    $venta_detalle->update([
                        "cantidad" => $vd["cantidad"],
                        "precio" => $vd["precio"],
                        "subtotal" => $vd["subtotal"],
                        "descuento" => $venta->descuento,
                        "subtotaltotal" => $sub_desc,
                    ]);

                    Producto::decrementarStock($producto, $vd["cantidad"], "SUCURSAL", $venta->sucursal_id);
                    // actualizar kardex
                    $kardex = KardexProducto::where("lugar", "SUCURSAL")
                        ->where("sucursal_id", $venta->sucursal_id)
                        ->where("producto_id", $vd["producto_id"])
                        ->where("tipo_registro", "VENTA")
                        ->where("registro_id", $vd["id"])
                        ->get()->first();


                    foreach ($array_update[$vd["producto_id"]] as $value) {
                        $producto_barra = ProductoBarra::find($value["id"]);
                        $producto_barra->update(["venta_detalle_id" => $venta_detalle->id]);
                    }
                    KardexProducto::actualizaRegistrosKardex($kardex->id, $kardex->producto_id, "SUCURSAL", $venta->sucursal_id);
                } else {
                    $nuevo_detalle = $venta->venta_detalles()->create([
                        "producto_id" => $vd["producto_id"],
                        "cantidad" => $vd["cantidad"],
                        "precio" => $vd["precio"],
                        "subtotal" => $vd["subtotal"],
                        "descuento" => $venta->descuento,
                        "subtotaltotal" => $sub_desc,
                    ]);

                    foreach ($array_update[$vd["producto_id"]] as $value) {
                        $producto_barra = ProductoBarra::find($value["id"]);
                        $producto_barra->update(["venta_detalle_id" => $venta_detalle->id]);
                    }
                    // registrar kardex
                    KardexProducto::registroEgreso("SUCURSAL", "VENTA", $nuevo_detalle->id, $nuevo_detalle->producto, $nuevo_detalle->cantidad, $nuevo_detalle->producto->precio, "VENTA DE PRODUCTO", $venta->sucursal_id);
                }
            }
            $datos_nuevo = HistorialAccion::getDetalleRegistro($venta, "ventas");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UNA VENTA',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'VENTAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("ventas.index")->with("bien", "Registro actualizado")
                ->with("venta_id", $venta->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Venta $venta)
    {
        DB::beginTransaction();
        try {
            $venta->producto_barras()->update(["venta_id", null, "venta_detalle_id" => null]);

            foreach ($venta->venta_detalles as $vd) {
                $producto = Producto::find($vd->producto_id);
                // ACTUALIZAR KARDEX
                $eliminar_kardex = KardexProducto::where("lugar", "SUCURSAL")
                    ->where("tipo_registro", "VENTA")
                    ->where("registro_id", $vd->id)
                    ->where("sucursal_id", $venta->sucursal_id)
                    ->where("producto_id", $vd->producto_id)
                    ->get()
                    ->first();
                $id_kardex = $eliminar_kardex->id;
                $id_producto = $eliminar_kardex->producto_id;
                $eliminar_kardex->delete();

                $anterior = KardexProducto::where("lugar", "SUCURSAL")
                    ->where("sucursal_id", $venta->sucursal_id)
                    ->where("producto_id", $id_producto)
                    ->where("id", "<", $id_kardex)
                    ->get()
                    ->last();
                $actualiza_desde = null;
                if ($anterior) {
                    $actualiza_desde = $anterior;
                } else {
                    // comprobar si existen registros posteriorres al actualizado
                    $siguiente = KardexProducto::where("lugar", "SUCURSAL")
                        ->where("sucursal_id", $venta->sucursal_id)
                        ->where("producto_id", $id_producto)
                        ->where("id", ">", $id_kardex)
                        ->get()->first();
                    if ($siguiente)
                        $actualiza_desde = $siguiente;
                }

                if ($actualiza_desde) {
                    // actualizar a partir de este registro los sgtes. registros
                    KardexProducto::actualizaRegistrosKardex($actualiza_desde->id, $actualiza_desde->producto_id, "SUCURSAL", $venta->sucursal_id);
                }

                // incrementar el stock
                Producto::incrementarStock($producto, $vd->cantidad, "SUCURSAL", $venta->sucursal_id);

                $vd->delete();
            }

            $datos_original = HistorialAccion::getDetalleRegistro($venta, "ventas");
            $venta->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UNA VENTA',
                'datos_original' => $datos_original,
                'modulo' => 'VENTAS',
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
