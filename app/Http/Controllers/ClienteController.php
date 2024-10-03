<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\HistorialAccion;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:1",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
        "ci.required" => "Este campo es obligatorio",
        "ci.unique" => "Este C.I./NIT ya se encuentra registrado"
    ];

    public function index()
    {
        return Inertia::render("Clientes/Index");
    }

    public function listado()
    {
        $clientes = Cliente::select("clientes.*");

        if (Auth::user()->tipo != 'ADMINISTRADOR') {

            $clientes->where("sucursal_id", Auth::user()->sucursal_id);
        }

        $clientes = $clientes->get();
        return response()->JSON([
            "clientes" => $clientes
        ]);
    }

    public function api(Request $request)
    {
        $clientes = Cliente::select("clientes.*");
        if (Auth::user()->tipo != 'ADMINISTRADOR') {

            $clientes->where("sucursal_id", Auth::user()->sucursal_id);
        }

        $clientes = $clientes->get();
        return response()->JSON(["data" => $clientes]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $clientes = Cliente::select("clientes.*");
        if (Auth::user()->tipo != 'ADMINISTRADOR') {

            $clientes->where("sucursal_id", Auth::user()->sucursal_id);
        }
        if (trim($search) != "") {
            $clientes->where("nombre", "LIKE", "%$search%");
        }

        $clientes = $clientes->paginate($request->itemsPerPage);
        return response()->JSON([
            "clientes" => $clientes
        ]);
    }

    public function store(Request $request)
    {
        $this->validacion['ci'] = [
            'required',
            'numeric',
            Rule::unique('clientes', 'ci')->where(function ($query) {
                return $query->where('ci', '!=', 0);
            }),
        ];

        if (Auth::user()->tipo != 'ADMINISTRADOR') {
            $request["sucursal_id"] = Auth::user()->sucursal_id;
        }

        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $request['fecha_registro'] = date('Y-m-d');
            // crear el Cliente
            $nueva_cliente = Cliente::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nueva_cliente, "clientes");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN CLIENTE',
                'datos_original' => $datos_original,
                'modulo' => 'CLIENTES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("clientes.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Cliente $cliente)
    {
        return response()->JSON($cliente);
    }

    public function update(Cliente $cliente, Request $request)
    {

        $this->validacion['ci'] = [
            'required',
            'numeric',
            Rule::unique('clientes', 'ci')
                ->ignore($cliente->id) // Ignorar el ID actual del cliente
                ->where(function ($query) {
                    return $query->where('ci', '!=', 0);
                }),
        ];
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($cliente, "clientes");
            $cliente->update(array_map('mb_strtoupper', $request->all()));
            $datos_nuevo = HistorialAccion::getDetalleRegistro($cliente, "clientes");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN CLIENTE',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'CLIENTES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("clientes.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Cliente $cliente)
    {
        DB::beginTransaction();
        try {
            $usos = Venta::where("cliente_id", $cliente->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }
            $datos_original = HistorialAccion::getDetalleRegistro($cliente, "clientes");
            $cliente->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN CLIENTE',
                'datos_original' => $datos_original,
                'modulo' => 'CLIENTES',
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
