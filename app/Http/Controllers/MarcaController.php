<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class MarcaController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:1",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
    ];

    public function index()
    {
        return Inertia::render("Marcas/Index");
    }

    public function listado()
    {
        $marcas = Marca::all();
        return response()->JSON([
            "marcas" => $marcas
        ]);
    }

    public function api(Request $request)
    {
        $usuarios = Marca::select("marcas.*");
        $usuarios = $usuarios->get();
        return response()->JSON(["data" => $usuarios]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $usuarios = Marca::select("marcas.*");

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
            // crear el Marca
            $nuevo_usuario = Marca::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_usuario, "marcas");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UNA MARCA',
                'datos_original' => $datos_original,
                'modulo' => 'MARCAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("marcas.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Marca $marca)
    {
        return response()->JSON($marca);
    }

    public function update(Marca $marca, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($marca, "marcas");
            $marca->update(array_map('mb_strtoupper', $request->all()));
            $datos_nuevo = HistorialAccion::getDetalleRegistro($marca, "marcas");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UNA MARCA',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'MARCAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("marcas.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Marca $marca)
    {
        DB::beginTransaction();
        try {
            $usos = Producto::where("marca_id", $marca->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }
            $datos_original = HistorialAccion::getDetalleRegistro($marca, "marcas");
            $marca->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UNA MARCA',
                'datos_original' => $datos_original,
                'modulo' => 'MARCAS',
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
