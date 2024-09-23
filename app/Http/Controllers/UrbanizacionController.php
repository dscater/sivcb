<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Lote;
use App\Models\Manzano;
use App\Models\Urbanizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class UrbanizacionController extends Controller
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
        return Inertia::render("Urbanizacions/Index");
    }

    public function listado()
    {
        $urbanizacions = Urbanizacion::select("urbanizacions.*")->get();
        return response()->JSON([
            "urbanizacions" => $urbanizacions
        ]);
    }

    public function api(Request $request)
    {
        // Log::debug($request);
        $urbanizacions = Urbanizacion::select("urbanizacions.*");
        $urbanizacions = $urbanizacions->get();
        return response()->JSON([
            "data" => $urbanizacions
        ]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $urbanizacions = Urbanizacion::select("urbanizacions.*");

        if (trim($search) != "") {
            $urbanizacions->where("nombre", "LIKE", "%$search%");
        }

        $urbanizacions = $urbanizacions->paginate($request->itemsPerPage);
        return response()->JSON([
            "urbanizacions" => $urbanizacions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $request['fecha_registro'] = date('Y-m-d');
            // crear el Urbanizacion
            $nueva_urbanizacion = Urbanizacion::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nueva_urbanizacion, "urbanizacions");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UNA URBANIZACIÓN',
                'datos_original' => $datos_original,
                'modulo' => 'URBANIZACIÓN',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("urbanizacions.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Urbanizacion $urbanizacion)
    {
        $urbanizacion = $urbanizacion->load(["manzanos.lotes"]);
        return Inertia::render("Urbanizacions/Show", compact("urbanizacion"));
    }

    public function update(Urbanizacion $urbanizacion, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($urbanizacion, "urbanizacions");
            $urbanizacion->update(array_map('mb_strtoupper', $request->all()));
            $datos_nuevo = HistorialAccion::getDetalleRegistro($urbanizacion, "urbanizacions");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UNA URBANIZACIÓN',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'URBANIZACIÓN',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("urbanizacions.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Urbanizacion $urbanizacion)
    {
        DB::beginTransaction();
        try {
            $usos = Manzano::where("urbanizacion_id", $urbanizacion->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }
            $usos = Lote::where("urbanizacion_id", $urbanizacion->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($urbanizacion, "urbanizacions");
            $urbanizacion->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UNA URBANIZACIÓN',
                'datos_original' => $datos_original,
                'modulo' => 'URBANIZACIÓN',
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
