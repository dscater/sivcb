<?php

namespace App\Http\Controllers;

use App\Models\AlmacenProducto;
use App\Models\HistorialAccion;
use App\Models\Producto;
use App\Models\ProductoBarra;
use App\Models\SucursalProducto;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProductoController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:1",
        "categoria_id" => "required",
        "marca_id" => "required",
        "unidad_medida_id" => "required",
        "precio" => "required",
        "stock_min" => "required",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
        "categoria_id.required" => "Este campo es obligatorio",
        "categoria_id.min" => "Debes ingresar al menos :min caracteres",
        "marca_id.required" => "Este campo es obligatorio",
        "unidad_medida_id.required" => "Este campo es obligatorio",
        "precio.required" => "Este campo es obligatorio",
        "stock_min.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("Productos/Index");
    }

    public function stock_productos()
    {
        return Inertia::render("Productos/StockProductos");
    }


    public function api_stock(Request $request)
    {
        $lugar = $request->lugar;
        $sucursal_id = $request->sucursal_id;

        $productos = Producto::with(["categoria", "marca", "unidad_medida"]);

        if ($lugar == 'ALMACÉN') {
            $productos = $productos->leftJoin("almacen_productos", "almacen_productos.producto_id", "=", "productos.id")
                ->select("productos.*", DB::raw("COALESCE(almacen_productos.stock_actual, 0) as stock_actual"));
        } else {
            $productos = $productos->leftJoin("sucursal_productos", function ($join) use ($sucursal_id) {
                $join->on("sucursal_productos.producto_id", "=", "productos.id")
                    ->where("sucursal_productos.sucursal_id", $sucursal_id);
            })
                ->select("productos.*", DB::raw("COALESCE(sucursal_productos.stock_actual, 0) as stock_actual"));
        }

        $productos = $productos->get();

        return response()->JSON(["data" => $productos]);
    }

    public function getStockByProductoSucursal(Request $request)
    {
        $producto_id = $request->producto_id;
        $sucursal_id = $request->sucursal_id;

        $stock = 0;
        $producto_barra = ProductoBarra::where("producto_id", $producto_id)
            ->where("sucursal_id", $sucursal_id)->get()->first();
        if ($producto_barra) {
            $stock = $producto_barra->stock_actual;
        }
        return response()->JSON($stock);
    }

    public function listado()
    {
        $productos = Producto::with(["categoria", "marca", "unidad_medida"])->select("productos.*")->get();
        return response()->JSON([
            "productos" => $productos
        ]);
    }

    public function api(Request $request)
    {
        // Log::debug($request);
        $productos = Producto::with(["categoria", "marca", "unidad_medida"])->select("productos.*");
        $productos = $productos->get();
        return response()->JSON(["data" => $productos]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $productos = Producto::with(["categoria", "marca", "unidad_medida"])->select("productos.*");

        if (trim($search) != "") {
            $productos->where("nombre", "LIKE", "%$search%");
        }

        $productos = $productos->paginate($request->itemsPerPage);
        return response()->JSON([
            "productos" => $productos
        ]);
    }

    public function store(Request $request)
    {
        if ($request->hasFile('imagen')) {
            $this->validacion['imagen'] = 'image|mimes:jpeg,jpg,png|max:4096';
        }
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            $request['fecha_registro'] = date('Y-m-d');
            // crear el Producto
            $nuevo_producto = Producto::create(array_map('mb_strtoupper', $request->except('imagen')));
            if ($request->hasFile('imagen')) {
                $file = $request->imagen;
                $nom_imagen = time() . '_' . $nuevo_producto->id . '.' . $file->getClientOriginalExtension();
                $nuevo_producto->imagen = $nom_imagen;
                $file->move(public_path() . '/imgs/productos/', $nom_imagen);
            }
            $nuevo_producto->save();

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_producto, "productos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("productos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Producto $producto)
    {
        return response()->JSON($producto);
    }

    public function update(Producto $producto, Request $request)
    {
        if ($request->hasFile('imagen')) {
            $this->validacion['imagen'] = 'image|mimes:jpeg,jpg,png|max:4096';
        }
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($producto, "productos");
            $producto->update(array_map('mb_strtoupper', $request->except('imagen')));
            if ($request->hasFile('imagen')) {
                $antiguo = $producto->imagen;
                if ($antiguo != 'default.png') {
                    \File::delete(public_path() . '/imgs/productos/' . $antiguo);
                }
                $file = $request->imagen;
                $nom_imagen = time() . '_' . $producto->id . '.' . $file->getClientOriginalExtension();
                $producto->imagen = $nom_imagen;
                $file->move(public_path() . '/imgs/productos/', $nom_imagen);
            }
            $producto->save();

            $datos_nuevo = HistorialAccion::getDetalleRegistro($producto, "productos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN PRODUCTO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'PRODUCTOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("productos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Producto $producto)
    {
        DB::beginTransaction();
        try {
            $usos = VentaDetalle::where("producto_id", $producto->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }
            $usos = SucursalProducto::where("producto_id", $producto->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }
            $usos = ProductoBarra::where("producto_id", $producto->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }

            $antiguo = $producto->imagen;
            if ($antiguo != 'default.png') {
                \File::delete(public_path() . '/imgs/productos/' . $antiguo);
            }
            $datos_original = HistorialAccion::getDetalleRegistro($producto, "productos");
            $producto->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN PRODUCTO',
                'datos_original' => $datos_original,
                'modulo' => 'PRODUCTOS',
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
