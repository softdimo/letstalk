<?php

namespace App\Http\Responses\entrenador;

use App\Mail\Disponibilidades\MailAprobacionDisponibilidad;
use App\Models\entrenador\DisponibilidadEntrenadores;
use App\Models\entrenador\EventoAgendaEntrenador;
use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Mail;

class AgendaEntrenadorStore implements Responsable
{
    public function toResponse($request)
    {
        set_time_limit(0);
        ini_set('max_execution_time', 0);

        $disponibilidad = request('hrs_disponibilidad', null);
        $fecha_disponibilidad = request('fecha_evento', null);
        $entrenador_id = request('trainer_id', null);
        $numDia = request('numero_dia', null);
        $dia = !is_null($numDia) ? implode("", $numDia) : null;
        $idRol = session('rol');
        $msgError = "";

        $array_disponibilidad = $this->llenarDiponibilidad($disponibilidad);
        DB::connection('mysql')->beginTransaction();

        try
        {
            if($array_disponibilidad != "error_exc_disp" &&
                ($array_disponibilidad != [] || count($array_disponibilidad) > 0))
            {
                foreach ($array_disponibilidad as $disp)
                {
                    $horas_disp = $this->disponibilidadEntrenadores($disp);

                    $hora_inicio = substr($horas_disp->horario, 0, 5);
                    $hora_fin = substr($horas_disp->horario, 8);

                    if(!is_null($entrenador_id) && $entrenador_id != "-1" && $idRol == 2)
                    {
                        $user = $this->traerNombreUsuario($entrenador_id);
                        $usuario = $user->usuario;
                        $state = 1; // Aprobado
                        $user_id = session('usuario_id');

                    } else
                    {
                        $usuario = session('username');
                        $state = 2;
                        $user_id = session('usuario_id');
                    }

                    $consultaDisponibilidades = $this->validarDisponibilidadUsuario($entrenador_id, $disp);

                    if ($consultaDisponibilidades > 0 &&
                        $consultaDisponibilidades != 'error_datos_disp')
                    {
                        return response()->json("ya_existe");
                    }

                    $insert_evento = EventoAgendaEntrenador::create([
                        'title' => 'Disp. ' . $usuario,
                        'description' => 'Hrs de disp. ' . $usuario,
                        'start_date' => $fecha_disponibilidad,
                        'start_time' => trim($hora_inicio),
                        'end_date' => $fecha_disponibilidad,
                        'end_time' => trim($hora_fin),
                        'color' => '#157347',
                        'state' => $state,// Pendiente Aprobación
                        'id_instructor' => $entrenador_id,
                        'id_usuario' => $user_id,
                        'id_horario' => $disp,
                        'num_dia' => intval($dia),
                        'clase_estado' => 10
                    ]);
                }

                if($insert_evento)
                {
                    DB::connection('mysql')->commit();
    
                    if(!is_null($state) && !empty($state) &&  $state == 2)
                    {
                        $this->enviarCorreoAdminAprobacion($user_id);
                    }
    
                    return response()->json("success_evento");
    
                } else
                {
                    DB::connection('mysql')->rollback();
                    $msgError = "error_evento";
                }
            } else
            {
                $msgError = "exception_evento";
            }

        } catch (Exception $e)
        {
            DB::connection('mysql')->rollback();
            dd($e);
            Logger("Error creando el evento: {$e}");
            $msgError = "exception_evento";
        }

        return response()->json($msgError);
    }

    private function llenarDiponibilidad($disponibilidad)
    {
        try
        {
            if(!is_null($disponibilidad) && !empty($disponibilidad))
            {
                $array_disponibilidad = explode(",", $disponibilidad);
            } else {
                $array_disponibilidad = [];
            }

        } catch (Exception $e)
        {
            return "error_exc_disp";
        }

        return $array_disponibilidad;
    }

    private function disponibilidadEntrenadores($disp)
    {
        try
        {
            return DisponibilidadEntrenadores::select('horario')
                                                        ->where('id_horario', $disp)
                                                        ->first();
            
        } catch (Exception $e)
        {
            return "error_disponibilidad";
        }
    }

    private function traerNombreUsuario($entrenador_id)
    {
        try {

            return User::select('usuario')->where('id_user', $entrenador_id)->whereNull('deleted_at')->first();

        } catch (Exception $e) {
            Logger("Error consultando el usuario: {$e}");
            return response()->json('error_evento');
        }
    }

    public function eliminarEvento()
    {
        $id_evento = request('id_evento', null);

        try
        {
            $evento = EventoAgendaEntrenador::find($id_evento);
            $evento->delete();

            return response()->json("success");

        } catch (Exception $e)
        {
            Logger("Error eliminando el evento: {$e}");
            return response()->json("error_exception");
        }
    }

    public function enviarCorreoAdminAprobacion($usuario_id)
    {
        // Consultamos la información del usuario logueado
        $datos_usuario = $this->traerDatosUsuario($usuario_id);
        $datos_admin = $this->traerDatosAdministrador();
        $traer_disponibilidad = $this->disponibilidadUsuario($usuario_id);

        if(isset($datos_usuario) && !empty($datos_usuario) && !is_null($datos_usuario)
           && $datos_usuario != "error_datos_usuario" &&
           isset($datos_admin) && !empty($datos_admin) && !is_null($datos_admin)
           && $datos_admin != "error_datos_admin")
        {
            if(!is_null($traer_disponibilidad) && $traer_disponibilidad != "error_datos_disp")
            {
                //Envio del correo
                Mail::to($datos_admin->correo)
                        ->send(new MailAprobacionDisponibilidad($datos_usuario,  $datos_admin, $traer_disponibilidad));
            }
        }
    }

    public function traerDatosUsuario($usuario_id)
    {
        try
        {
            return User::find($usuario_id);

        } catch (Exception $e)
        {
            Logger("Error consultando los datos del usuario: {$e}");
            return "error_datos_usuario";
        }
    }

    public function traerDatosAdministrador()
    {
        try
        {
            return User::where('id_rol', 2)->first();

        } catch (Exception $e)
        {
            Logger("Error consultando los datos del usuario administrador: {$e}");
            return "error_datos_admin";
        }
    }

    public function validarDisponibilidadUsuario($usuario_id, $disp)
    {
        try
        {
            return EventoAgendaEntrenador::where('id_usuario', $usuario_id)
                                            ->where('id_horario', $disp)
                                            ->whereIn('state', [1,2])
                                            ->get()
                                            ->count();
        } catch (Exception $e)
        {
            Logger("Error consultando los datos del usuario administrador: {$e}");
            return "error_datos_disp";
        }
    }

    public function disponibilidadUsuario($usuario_id)
    {
        try
        {
            return EventoAgendaEntrenador::where('id_usuario', $usuario_id)
                                            ->whereIn('state', [1,2])
                                            ->get();
        } catch (Exception $e)
        {
            Logger("Error consultando los datos del usuario administrador: {$e}");
            return "error_datos_disp";
        }
    }

}
