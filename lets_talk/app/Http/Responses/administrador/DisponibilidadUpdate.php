<?php

namespace App\Http\Responses\administrador;

use App\Models\entrenador\EventoAgendaEntrenador;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DisponibilidadUpdate implements Responsable
{
    public function toResponse($request)
    {
        $disponibilidadId = intval(request("disponibilidad_id", null));
        $estadoId = intval(request("estado_id", null));

        DB::connection('mysql')->beginTransaction();

        try {
            if($estadoId == 4 || $estadoId == "4") {
                $hoy = Carbon::parse(now())->format('Y-m-d H:i:s');

                $actualizacionIndividualDiponibilidades = EventoAgendaEntrenador::where('id', $disponibilidadId)
                    ->update(
                        [
                            'state' => $estadoId,
                            'deleted_at' => $hoy
                        ]
                    );

            } else {
                $actualizacionIndividualDiponibilidades = EventoAgendaEntrenador::where('id', $disponibilidadId)
                    ->update(
                        [
                            'state' => $estadoId,
                            'deleted_at' => null
                        ]
                    );
            }

            if($actualizacionIndividualDiponibilidades) {
                DB::connection('mysql')->commit();
                return response()->json("success");
            } else {
                DB::connection('mysql')->rollback();
                return response()->json("error_update");
            }

        } catch (Exception $e) {
            DB::connection('mysql')->rollback();
            return response()->json("error_exception");
        }
    }
}
