<?php

namespace App\Http\Responses\entrenador;

use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Log\Logger;
use App\Models\entrenador\EventoAgendaEntrenador;

class DiponibilidadesIndividualUpdate implements Responsable
{
    public function toResponse($request)
    {
        DB::connection('mysql')->beginTransaction();

        $estado = request('idEstado', null);
        $idEvento = request('idDisponibilidad', null);

        $consultaEvento = $this->consultarEventosPorID($idEvento);

        if($consultaEvento == "error_exception") {
            return response()->json("error_exception");
        } else {
            if(Carbon::now()->format('Y-m-d') > $consultaEvento->end_date) {
                $actualizacionEventoVencido = EventoAgendaEntrenador::where('id', $consultaEvento->id)
                    ->update([
                            'state' => 11, // Expired
                    ]);
            } else {
                $actualizacionIndividualDiponibilidad = EventoAgendaEntrenador::where('id', $consultaEvento->id)
                        ->update([
                                'state' => $estado,
                        ]);
            }

            try {
                if( (isset($actualizacionEventoVencido) && $actualizacionEventoVencido) || (isset($actualizacionIndividualDiponibilidad) && $actualizacionIndividualDiponibilidad) )
                {
                    DB::connection('mysql')->commit();
                    return response()->json("exito");
                } else
                {
                    DB::connection('mysql')->rollback();
                    return response()->json("error");
                }

            } catch (Exception $e) {
                dd($e);
                DB::connection('mysql')->rollback();
                return response()->json("error_exception");
            }
        }
    } // FIN toResponse

    // ===========================================================

    public function consultarEventosPorID($idEvento)
    {
        try {
            $evento = EventoAgendaEntrenador::where('id', $idEvento)->first();

            if(!is_null($evento) || !empty($evento)) {
                return $evento;
            } else {
                return null;
            }
        } catch (Exception $e) {
            return "error_exception";
        }
    }
}
