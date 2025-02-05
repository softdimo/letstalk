<?php

namespace App\Http\Responses\entrenador;

use App\Models\entrenador\EventoAgendaEntrenador;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgendaEntrenadorUpdate implements Responsable
{
    private $id_usuario;

    public function __construct($id)
    {
        $this->id_usuario = $id;
    }
    public function toResponse($request)
    {
        $titulo = request('title', null);
        $descripcion = request('description', null);
        $inicio = request('start', null);
        $hora_inicio = request('start_time', null);
        $fin = request('end', null);
        $hora_fin = request('end_time', null);
        $color = request('color', null);
        $id_evento = request('id_evento', null);
        $usuario_id = $this->id_usuario;
        DB::connection('mysql')->beginTransaction();

        try
        {

            $update_evento = EventoAgendaEntrenador::where('id', $id_evento)
                                    ->where('state', 1)
                                    ->whereNull('deleted_at')
                                    ->where('id_usuario', $usuario_id)
                                    ->update(
                                        [
                                            'title' => trim($titulo),
                                            'description' => !is_null($descripcion) ? trim($descripcion) : null,
                                            'start_date' => trim($inicio),
                                            'start_time' => trim($hora_inicio),
                                            'end_date' => trim($fin),
                                            'end_time' => trim($hora_fin),
                                            'color' => $color
                                        ]
                                    );

            if($update_evento)
            {
                DB::connection('mysql')->commit();
                return response()->json("success_evento");
            } else {
                DB::connection('mysql')->rollback();
                return response()->json("error_evento");
            }

        } catch (Exception $e)
        {
            DB::connection('mysql')->rollback();
            return response()->json('exception_evento');
        }
    }
}
