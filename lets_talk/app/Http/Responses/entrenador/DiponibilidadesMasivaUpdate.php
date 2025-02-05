<?php

namespace App\Http\Responses\entrenador;

use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Log\Logger;
use App\Models\entrenador\EventoAgendaEntrenador;

class DiponibilidadesMasivaUpdate implements Responsable
{
    public function toResponse($request)
    {
        $estado = request("idEstado", null);
        $idEvento = $request['idsDisponibilidades'];
        $consultaEvento = $this->consultarEventosPorID($idEvento);
        $eventosVencidos = array();

        if($consultaEvento == "error_exception")
        {
            return response()->json("error_exception");
        } else
        {
            foreach ($consultaEvento as $evento)
            {
                if(Carbon::now()->format('Y-m-d') > $evento['end_date'])
                {
                    array_push($eventosVencidos, $evento['id']);
                }
            }
        }

        DB::connection('mysql')->beginTransaction();
        
        try
        {
            if(isset($eventosVencidos) && !empty($eventosVencidos))
            {
                $actualizacionEventosVencidos = EventoAgendaEntrenador::whereIn('id', $eventosVencidos)
                        ->update(
                            [
                                'state' => 11, // Expired
                            ]
                        );
            } else
            {
                $actualizacionMasivaDiponibilidades = EventoAgendaEntrenador::whereIn('id', $idEvento)
                        ->update(
                            [
                                'state' => $estado,
                            ]
                        );
            }

            if((isset($actualizacionMasivaDiponibilidades) && $actualizacionMasivaDiponibilidades) ||
               (isset($actualizacionEventosVencidos) && $actualizacionEventosVencidos))
            {
                DB::connection('mysql')->commit();
                return response()->json("exito");
            } else
            {
                DB::connection('mysql')->rollback();
                return response()->json("error");
            }

        } catch (Exception $e)
        {
            DB::connection('mysql')->rollback();
            return response()->json("error_exception");
        }
    }

    public function consultarEventosPorID($idEvento)
    {
        try
        {
            $eventos = EventoAgendaEntrenador::whereIn('id', $idEvento)->get();

            if(!is_null($eventos) || !empty($eventos))
            {
                return $eventos;
            } else
            {
                return null;
            }

        } catch (Exception $e)
        {
            return "error_exception";
        }
    }
}
