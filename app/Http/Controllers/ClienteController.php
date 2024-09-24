<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:1",
        "paterno" => "required|min:1",
        "ci" => "required|min:1",
        "ci_exp" => "required",
        "fono" => "required|min:1",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
        "paterno.required" => "Este campo es obligatorio",
        "paterno.min" => "Debes ingresar al menos :min caracteres",
        "ci.required" => "Este campo es obligatorio",
        "ci.unique" => "Este C.I. ya fue registrado",
        "ci.min" => "Debes ingresar al menos :min caracteres",
        "ci_exp.required" => "Este campo es obligatorio",
        "email.unique" => "El correo electrónico ya fue registrado",
        "dir.required" => "Este campo es obligatorio",
        "dir.min" => "Debes ingresar al menos :min caracteres",
        "fono.required" => "Este campo es obligatorio",
        "fono.min" => "Debes ingresar al menos :min caracteres",
        "tipo.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("Clientes/Index");
    }

    public function listado()
    {
        $clientes = Cliente::with(["user"])->get();
        return response()->JSON([
            "clientes" => $clientes
        ]);
    }

    public function api(Request $request)
    {
        // Log::debug($request);
        $usuarios = User::with(["cliente"])->where("tipo", "CLIENTE");
        $usuarios = $usuarios->get();
        return response()->JSON(["data" => $usuarios]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $usuarios = User::with(["cliente"])->where("tipo", "CLIENTE");

        if (trim($search) != "") {
            $usuarios->where("nombre", "LIKE", "%$search%");
            $usuarios->orWhere("paterno", "LIKE", "%$search%");
            $usuarios->orWhere("materno", "LIKE", "%$search%");
            $usuarios->orWhere("ci", "LIKE", "%$search%");
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
            $cont = 0;
            do {
                $nombre_usuario = User::getNombreUsuario($request->nombre, $request->paterno);
                if ($cont > 0) {
                    $nombre_usuario = $nombre_usuario . $cont;
                }
                $request['usuario'] = $nombre_usuario;
                $cont++;
            } while (User::where('usuario', $nombre_usuario)->get()->first());

            $request['password'] = 'NoNulo';
            $request['fecha_registro'] = date('Y-m-d');
            $request['tipo'] = 'CLIENTE';

            // crear el Cliente
            $nuevo_usuario = User::create(array_map('mb_strtoupper', $request->except('foto')));
            $nuevo_usuario->password = Hash::make($request->ci);
            $nuevo_usuario->save();

            // crear el cliente
            $nuevo_usuario->cliente()->create();

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_usuario, "users");
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
        return response()->JSON($cliente->load(["user"]));
    }

    public function update(Cliente $cliente, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $user = $cliente->user;
            $datos_original = HistorialAccion::getDetalleRegistro($user, "users");
            $user->update(array_map('mb_strtoupper', $request->except('foto')));
            if ($request->hasFile('foto')) {
                $antiguo = $user->foto;
                if ($antiguo != 'default.png') {
                    \File::delete(public_path() . '/imgs/users/' . $antiguo);
                }
                $file = $request->foto;
                $nom_foto = time() . '_' . $user->usuario . '.' . $file->getClientOriginalExtension();
                $user->foto = $nom_foto;
                $file->move(public_path() . '/imgs/users/', $nom_foto);
            }
            $user->save();

            $datos_nuevo = HistorialAccion::getDetalleRegistro($user, "users");
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

    public function update_estado(Cliente $cliente, Request $request)
    {
        $request->validate(["estado_cliente" => "required"], ["estado_cliente.required", "Debes seleccionar una opción"]);
        DB::beginTransaction();
        try {
            if ($cliente->estado_cliente != $request->estado_cliente) {
                $user = $cliente->user;
                $datos_original = HistorialAccion::getDetalleRegistro($user, "users");

                $cliente->update([
                    "estado_cliente" => $request->estado_cliente
                ]);
                $cliente->venta_lotes()->update([
                    "estado_cliente" => $request->estado_cliente
                ]);

                $fecha_actual = date("Y-m-d");

                $notificacion = Notificacion::where("fecha", $fecha_actual)
                    ->where("tipo_notificacion", "ESTADO CLIENTE")
                    ->where("registro_id", $cliente->id)
                    ->get()->first();
                if (!$notificacion) {
                    $notificacion = Notificacion::create([
                        "descripcion" => "SE CAMBIO EL ESTADO DEL CLIENTE " . $cliente->user->full_name . " A " . $request->estado_cliente,
                        "fecha" => $fecha_actual,
                        "hora" => date("H:i:s"),
                        "tipo_notificacion" => "ESTADO CLIENTE",
                        "registro_id" => $cliente->id,
                    ]);
                }

                $users = User::whereIn("tipo", ["ADMINISTRADOR", "SUPERVISOR", "AGENTE INMOBILIARIO"])->get();
                foreach ($users as $value) {
                    NotificacionUser::create([
                        "notificacion_id" => $notificacion->id,
                        "user_id" => $value->id
                    ]);
                }

                NotificacionUser::create([
                    "notificacion_id" => $notificacion->id,
                    "user_id" => $cliente->user->id
                ]);

                $cliente->fecha_estado = null;
                $cliente->fechan = null;
                if ($request->estado_cliente == 'DISPENSA') {
                    $cliente->fecha_estado = $fecha_actual;
                    $fechan = date("Y-m-d", strtotime($fecha_actual . '+6 month'));
                    $cliente->fechan = $fechan;
                }

                $cliente->save();

                $datos_nuevo = HistorialAccion::getDetalleRegistro($user, "users");
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
            }

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
            $user = $cliente->user;
            $usos = VentaLote::where("cliente_id", $user->cliente->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }
            $usos = Pago::where("cliente_id", $user->cliente->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }

            $antiguo = $user->foto;
            if ($antiguo != 'default.png') {
                \File::delete(public_path() . '/imgs/users/' . $antiguo);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($user, "users");
            $cliente->delete();
            $user->delete();
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
