<?php

namespace App\Http\Responses\administrador;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use App\Models\entrenador\EventoAgendaEntrenador;
class HorarioState implements Responsable
{
    public function toResponse($request)
    {
        $idHorario = request('id_horario', null);
        $estadoDisp = " (CASE WHEN id_estado = 1 THEN 6 ELSE 1 END) ";
        $estadoAgendaEntrenador = " (CASE WHEN state = 1 THEN 6 ELSE 1 END) ";
        DB::connection('mysql')->beginTransaction();

        try
        {
            $estadoDisponibilidad = DB::table('disponibilidad_entrenadores')
                                        ->where('id_horario', $idHorario)
                                        ->update([
                                            'id_estado' => DB::raw($estadoDisp)
                                        ]);

            $verificarDisponibilidadesEntrenadores = $this->verificarDisponibilidades($idHorario);

            if($verificarDisponibilidadesEntrenadores != "error_exception" &&
                !is_null($verificarDisponibilidadesEntrenadores))
            {
                $estadoAgenda = DB::table('evento_agenda_entrenador')
                                    ->where('id_horario', $idHorario)
                                    ->update([
                                        'state' => DB::raw($estadoAgendaEntrenador)
                                    ]);
            } else
            {
                $estadoAgenda = true;
            }

            if ((isset($estadoDisponibilidad) && $estadoDisponibilidad) &&
                (isset($estadoAgenda) && $estadoAgenda))
            {
                DB::connection('mysql')->commit();
                sleep(2);
                return response()->json("success");
            } else
            {
                DB::connection('mysql')->rollback();
                return response()->json('no_inactived');
            }
        } catch (Exception $e)
        {
            DB::connection('mysql')->rollback();
            dd($e);
            return response()->json('error_exception');
        }
    }

    private function verificarDisponibilidades($idHorario)
    {
        try
        {
            $disponibilidades = EventoAgendaEntrenador::where('id_horario', $idHorario)->get()->toarray();

            if(!empty($disponibilidades) || $disponibilidades != [])
            {
                return $disponibilidades;
            } else
            {
                return null;
            }

        } catch (Exception $e)
        {
            DB::connection('mysql')->rollback();
            return response()->json('error_exception');
        }
    }
}
