<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class KardexProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        "lugar",
        "sucursal_id",
        "tipo_registro",
        "registro_id",
        "producto_id",
        "detalle",
        "precio",
        "tipo_is",
        "cantidad_ingreso",
        "cantidad_salida",
        "cantidad_saldo",
        "cu",
        "monto_ingreso",
        "monto_salida",
        "monto_saldo",
        "fecha",
    ];


    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    // REGISTRAR INGRESO
    public static function registroIngreso($lugar, $tipo_registro, $registro_id = 0, Producto $producto, $cantidad, $precio, $detalle = "", $sucursal_id = null)
    {
        //buscar el ultimo registro y usar sus valores
        $ultimo = KardexProducto::where('producto_id', $producto->id)
            ->where("lugar", $lugar);

        if ($sucursal_id) {
            $ultimo->where("sucursal_id", $sucursal_id);
        }

        $ultimo = $ultimo->orderBy('created_at', 'asc')
            ->get()
            ->last();
        $monto = (float)$cantidad * (float)$precio;
        if ($ultimo) {
            if (!$detalle || $detalle == "") {
                $detalle = "INGRESO DE PRODUCTO";
            }
            KardexProducto::create([
                'lugar' => $lugar,
                "sucursal_id" => $sucursal_id,
                'tipo_registro' => $tipo_registro, //INGRESO, EGRESO, VENTA, COMPRA,etc...
                'registro_id' => $registro_id,
                'producto_id' => $producto->id,
                'detalle' => $detalle,
                'precio' => $precio,
                'tipo_is' => 'INGRESO',
                'cantidad_ingreso' => $cantidad,
                'cantidad_saldo' => (float)$ultimo->cantidad_saldo + (float)$cantidad,
                'cu' => $producto->precio,
                'monto_ingreso' => $monto,
                'monto_saldo' => (float)$ultimo->monto_saldo + $monto,
                'fecha' => date('Y-m-d'),
            ]);
        } else {
            $detalle = "VALOR INICIAL";
            KardexProducto::create([
                'lugar' => $lugar,
                "sucursal_id" => $sucursal_id,
                'tipo_registro' => $tipo_registro, //INGRESO, EGRESO, VENTA,etc...
                'registro_id' => $registro_id,
                'producto_id' => $producto->id,
                'detalle' => $detalle,
                'precio' => $precio,
                'tipo_is' => 'INGRESO',
                'cantidad_ingreso' => $cantidad,
                'cantidad_saldo' => (float)$cantidad,
                'cu' => $producto->precio,
                'monto_ingreso' => $monto,
                'monto_saldo' =>  $monto,
                'fecha' => date('Y-m-d'),
            ]);
        }

        // INCREMENTAR STOCK
        if ($sucursal_id) {
            Producto::incrementarStock($producto, $cantidad, $lugar, $sucursal_id);
        } else {
            Producto::incrementarStock($producto, $cantidad, $lugar);
        }

        return true;
    }

    // REGISTRAR EGRESO
    public static function registroEgreso($lugar, $tipo_registro, $registro_id = 0, Producto $producto, $cantidad, $precio, $detalle = "", $sucursal_id = null)
    {
        //buscar el ultimo registro y usar sus valores
        $ultimo = KardexProducto::where('producto_id', $producto->id)
            ->where("lugar", $lugar);
        if ($sucursal_id) {
            $ultimo->where("sucursal_id", $sucursal_id);
        }
        $ultimo = $ultimo->orderBy('created_at', 'asc')
            ->get()
            ->last();
        $monto = (float)$cantidad * (float)$precio;

        if (!$detalle || $detalle == "") {
            $detalle = "SALIDA DE PRODUCTO";
        }

        KardexProducto::create([
            'lugar' => $lugar,
            "sucursal_id" => $sucursal_id,
            'tipo_registro' => $tipo_registro,
            'registro_id' => $registro_id,
            'producto_id' => $producto->id,
            'detalle' => $detalle,
            'precio' => $precio,
            'tipo_is' => 'EGRESO',
            'cantidad_salida' => $cantidad,
            'cantidad_saldo' => (float)$ultimo->cantidad_saldo - (float)$cantidad,
            'cu' => $producto->precio,
            'monto_salida' => $monto,
            'monto_saldo' => (float)$ultimo->monto_saldo - $monto,
            'fecha' => date('Y-m-d'),
        ]);

        if ($sucursal_id) {
            Producto::decrementarStock($producto, $cantidad, $lugar, $sucursal_id);
        } else {
            Producto::decrementarStock($producto, $cantidad, $lugar);
        }

        return true;
    }

    // ACTUALIZA REGISTROS KARDEX
    // FUNCIÓN QUE ACTUALIZA LOS REGISTROS DEL KARDEX DE UN LUGAR
    // SOLO ACTUALIZARA LOS REGISTROS POSTERIORES AL REGISTRO ACTUALIZADO
    public static function actualizaRegistrosKardex($id, $producto_id, $lugar, $sucursal_id = null)
    {
        $siguientes = KardexProducto::where("lugar", $lugar)
            ->where("producto_id", $producto_id)
            ->where("id", ">=", $id);

        if ($lugar == 'SUCURSAL') {
            $siguientes->where("sucursal_id", $sucursal_id);
        }

        $siguientes = $siguientes->get();

        foreach ($siguientes as $item) {
            $anterior = KardexProducto::where("lugar", $lugar)
                ->where("producto_id", $producto_id)
                ->where("id", "<", $item->id);

            if ($lugar == 'SUCURSAL') {
                $anterior->where("sucursal_id", $sucursal_id);
            }

            $anterior = $anterior->get()->last();

            $datos_actualizacion = [
                "lugar" => $item->lugar,
                "precio" => 0,
                "cantidad_ingreso" => NULL,
                "cantidad_salida" => NULL,
                "cantidad_saldo" => 0,
                "cu" => 0,
                "monto_ingreso" => NULL,
                "monto_salida" => NULL,
                "monto_saldo" => 0,
            ];
            switch ($item->tipo_registro) {
                case 'INGRESO':
                    $ingreso_producto = IngresoProducto::find($item->registro_id);
                    $monto = (float)$ingreso_producto->cantidad * (float)$ingreso_producto->producto->precio;
                    if ($anterior) {
                        $datos_actualizacion["precio"] = $ingreso_producto->producto->precio;
                        $datos_actualizacion["cantidad_ingreso"] =  $ingreso_producto->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$anterior->cantidad_saldo + (float)$ingreso_producto->cantidad;
                        $datos_actualizacion["cu"] = $ingreso_producto->producto->precio;
                        $datos_actualizacion["monto_ingreso"] = $monto;
                        $datos_actualizacion["monto_saldo"] = (float)$anterior->monto_saldo + $monto;
                    } else {
                        $datos_actualizacion["precio"] = $ingreso_producto->producto->precio;
                        $datos_actualizacion["cantidad_ingreso"] =  $ingreso_producto->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$ingreso_producto->cantidad;
                        $datos_actualizacion["cu"] = $ingreso_producto->producto->precio;
                        $datos_actualizacion["monto_ingreso"] = $monto;
                        $datos_actualizacion["monto_saldo"] = $monto;
                    }
                    break;
                case 'SALIDA':
                    $salida_producto = SalidaProducto::find($item->registro_id);
                    $monto = (float)$salida_producto->cantidad * (float)$salida_producto->producto->precio;

                    if ($anterior) {
                        $datos_actualizacion["precio"] = $salida_producto->producto->precio;
                        $datos_actualizacion["cantidad_salida"] =  $salida_producto->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$anterior->cantidad_saldo - (float)$salida_producto->cantidad;
                        $datos_actualizacion["cu"] = $salida_producto->producto->precio;
                        $datos_actualizacion["monto_salida"] = $monto;
                        $datos_actualizacion["monto_saldo"] =  (float)$anterior->monto_saldo - $monto;
                    } else {
                        $datos_actualizacion["precio"] = $salida_producto->producto->precio;
                        $datos_actualizacion["cantidad_salida"] =  $salida_producto->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$salida_producto->cantidad * (-1);
                        $datos_actualizacion["cu"] = $salida_producto->producto->precio;
                        $datos_actualizacion["monto_salida"] = $monto;
                        $datos_actualizacion["monto_saldo"] = $monto * (-1);
                    }

                    break;
                case 'DISTRIBUCION':
                    $distribucion_detalle = DistribucionDetalle::find($item->registro_id);
                    $monto = (float)$distribucion_detalle->cantidad * (float)$distribucion_detalle->producto->precio;
                    if ($item->tipo_is == 'INGRESO') {
                        if ($anterior) {
                            $datos_actualizacion["precio"] = $distribucion_detalle->producto->precio;
                            $datos_actualizacion["cantidad_ingreso"] =  $distribucion_detalle->cantidad;
                            $datos_actualizacion["cantidad_saldo"] = (float)$anterior->cantidad_saldo + (float)$distribucion_detalle->cantidad;
                            $datos_actualizacion["cu"] = $distribucion_detalle->producto->precio;
                            $datos_actualizacion["monto_ingreso"] = $monto;
                            $datos_actualizacion["monto_saldo"] = (float)$anterior->monto_saldo + $monto;
                        } else {
                            $datos_actualizacion["precio"] = $distribucion_detalle->producto->precio;
                            $datos_actualizacion["cantidad_ingreso"] =  $distribucion_detalle->cantidad;
                            $datos_actualizacion["cantidad_saldo"] = (float)$distribucion_detalle->cantidad;
                            $datos_actualizacion["cu"] = $distribucion_detalle->producto->precio;
                            $datos_actualizacion["monto_ingreso"] = $monto;
                            $datos_actualizacion["monto_saldo"] = $monto;
                        }
                    } else {
                        if ($anterior) {
                            $datos_actualizacion["precio"] = $distribucion_detalle->producto->precio;
                            $datos_actualizacion["cantidad_salida"] =  $distribucion_detalle->cantidad;
                            $datos_actualizacion["cantidad_saldo"] = (float)$anterior->cantidad_saldo - (float)$distribucion_detalle->cantidad;
                            $datos_actualizacion["cu"] = $distribucion_detalle->producto->precio;
                            $datos_actualizacion["monto_salida"] = $monto;
                            $datos_actualizacion["monto_saldo"] =  (float)$anterior->monto_saldo - $monto;
                        } else {
                            $datos_actualizacion["precio"] = $distribucion_detalle->producto->precio;
                            $datos_actualizacion["cantidad_salida"] =  $distribucion_detalle->cantidad;
                            $datos_actualizacion["cantidad_saldo"] = (float)$distribucion_detalle->cantidad * (-1);
                            $datos_actualizacion["cu"] = $distribucion_detalle->producto->precio;
                            $datos_actualizacion["monto_salida"] = $monto;
                            $datos_actualizacion["monto_saldo"] = $monto * (-1);
                        }
                    }
                    break;
                case 'VENTA':
                    $venta_detalle = VentaDetalle::find($item->registro_id);
                    $monto = (float)$venta_detalle->cantidad * (float)$venta_detalle->precio;
                    if ($anterior) {
                        $datos_actualizacion["precio"] = $venta_detalle->precio;
                        $datos_actualizacion["cantidad_salida"] =  $venta_detalle->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$anterior->cantidad_saldo - (float)$venta_detalle->cantidad;
                        $datos_actualizacion["cu"] = $venta_detalle->precio;
                        $datos_actualizacion["monto_salida"] = $monto;
                        $datos_actualizacion["monto_saldo"] =  (float)$anterior->monto_saldo - $monto;
                    } else {
                        $datos_actualizacion["precio"] = $venta_detalle->precio;
                        $datos_actualizacion["cantidad_salida"] =  $venta_detalle->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$venta_detalle->cantidad * (-1);
                        $datos_actualizacion["cu"] = $venta_detalle->precio;
                        $datos_actualizacion["monto_salida"] = $monto;
                        $datos_actualizacion["monto_saldo"] = $monto * (-1);
                    }
                    break;
            }

            $item->update($datos_actualizacion);
        }
    }
}
