<?php

namespace App\Http\Responses\administrador;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;

class DisponibilidadShow implements Responsable
{
    public function toResponse($request) {}

    public function traerDisponibilidades()
    {
        try
        {
            return DB::table('evento_agenda_entrenador')
                                    ->join('usuarios', 'usuarios.id_user', '=', 'evento_agenda_entrenador.id_instructor')
                                    ->join('estados', 'estados.id_estado', '=', 'evento_agenda_entrenador.state')
                                    ->select(
                                        'evento_agenda_entrenador.id',
                                        'evento_agenda_entrenador.title',
                                        'evento_agenda_entrenador.description',
                                        'evento_agenda_entrenador.start_date',
                                        'evento_agenda_entrenador.start_time',
                                        'evento_agenda_entrenador.end_date',
                                        'evento_agenda_entrenador.end_time',
                                        'evento_agenda_entrenador.state',
                                        'evento_agenda_entrenador.id_instructor',
                                        'usuarios.nombres',
                                        'usuarios.apellidos',
                                        'estados.descripcion_estado'
                                    )
                                    ->where('usuarios.estado', 1)
                                    ->whereNull('usuarios.deleted_at')
                                    ->whereNull('evento_agenda_entrenador.deleted_at')
                                    ->whereIn('evento_agenda_entrenador.state', [1,2,3,11])
                                    ->orderBy('evento_agenda_entrenador.id', 'DESC')
                                    ->get();
        } catch (Exception $e)
        {
            alert()->error("Error', 'An error has occurred, try again, if the problem persists contact support.!");
            return redirect()->to(route('administrador.index'));
        }
    }

    public function disponibilidadPorID($request)
    {
        try {
            $id = request('id_diponibilidad', null);
            $numeroDia = request('numero_dia', null);

            $queryDisponibilidades = $this->consultarDisponibilidades($id, $numeroDia);

            if(isset($queryDisponibilidades) && !empty($queryDisponibilidades) &&
               !is_null($queryDisponibilidades) && count($queryDisponibilidades))
            {
                return response()->json($queryDisponibilidades);
            } else {
                return response()->json("no_datos");
            }

        } catch (Exception $e) {
            return response()->json("error_exception");
        }
    }

    private function consultarDisponibilidades($idHorario, $numDia)
    {
        return DB::table('evento_agenda_entrenador')
                ->join('usuarios', 'usuarios.id_user', '=', 'evento_agenda_entrenador.id_instructor')
                ->leftJoin('tipo_ingles', 'tipo_ingles.id', '=', 'usuarios.id_tipo_ingles')
                ->select(
                            'evento_agenda_entrenador.id_instructor',
                            'usuarios.nombres',
                            'usuarios.apellidos',
                            'tipo_ingles.descripcion'
                        )
                ->whereIn('usuarios.id_rol', [1,2])
                ->where('usuarios.estado', 1)
                ->whereNull('usuarios.deleted_at')
                ->where('evento_agenda_entrenador.state', 1)
                ->whereNull('evento_agenda_entrenador.deleted_at')
                ->where('evento_agenda_entrenador.id_horario', $idHorario)
                ->where('evento_agenda_entrenador.num_dia', $numDia)
                ->get();
    }
}
