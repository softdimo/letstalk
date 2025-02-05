<?php

namespace App\Http\Responses\estudiante;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use App\Models\estudiante\Credito;
use App\Models\usuarios\Reserva;
use Exception;

class CreditosShow implements Responsable
{
    public function toResponse($request)
    {
        try
        {
            return Credito::select(
                DB::raw('DATE_FORMAT(FROM_UNIXTIME(fecha_credito), "%d-%m-%Y") as fecha_credito'),
                'paquete',
                DB::raw('COUNT(*) as cantidad_total_paquete'),
                DB::raw('SUM(CASE WHEN id_estado = 8 THEN 1 ELSE 0 END) as cantidad_consumida'),
                DB::raw('SUM(CASE WHEN id_estado = 7 THEN 1 ELSE 0 END) as cantidad_disponible')
            )
            ->where('id_estudiante', $request)
            ->whereNull('deleted_at')
            ->groupBy(
                DB::raw('DATE_FORMAT(FROM_UNIXTIME(fecha_credito), "%d-%m-%Y")'),
                'paquete'
            )
            ->orderBy('paquete', 'desc')
            ->get();

        } catch (Exception $e)
        {
            return 'error_exception';
        }
    }

    public function totalCreditosDisponibles($idEstudiante)
    {
        try
        {
            // Consulta para obtener la suma total de créditos disponibles
            $creditos = Credito::where('id_estudiante', $idEstudiante)
                    ->where('id_estado', 7)
                    ->whereNull('deleted_at')
                    ->count();

        if($creditos > 0 || $creditos > "0")
        {
            return $creditos;
        } else {
            return "0";
        }

        } catch (Exception $e)
        {
            return 'error_exception';
        }
    }

    public function sesionesEstudiante($idEstudiante)
    {
        try
        {
            return Reserva::leftjoin('evento_agenda_entrenador','evento_agenda_entrenador.id','=','reservas.id_trainer_horario')
                ->leftjoin('usuarios as instructor','instructor.id_user','=','reservas.id_instructor')
                ->select(
                    'reservas.id_estudiante',
                    'reservas.id_instructor',
                    DB::raw("CONCAT(instructor.nombres, ' ', instructor.apellidos) AS nombre_instructor"),
                    'reservas.id_trainer_horario',
                    'start_date',
                    'start_time',
                    'link_meet'
                )
                ->where('id_estudiante', $idEstudiante)
                ->orderBy('start_date', 'desc')
                ->orderBy('start_time', 'desc')
                ->get();

        } catch (Exception $e)
        {
            return 'error_exception';
        }
    }
}
