<?php

namespace App\Http\Responses\administrador;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;

class DisponibilidadesLibres
{
    public function obtenerDisponibilidadesLibres()
    {
        try
        {
            return DB::table('evento_agenda_entrenador')
                ->join('estados', 'estados.id_estado', '=', 'evento_agenda_entrenador.clase_estado')
                ->join('usuarios', 'usuarios.id_user', '=', 'evento_agenda_entrenador.id_instructor')
                ->join('roles', 'roles.id_rol', '=', 'usuarios.id_rol')
                ->join('tipo_ingles', 'tipo_ingles.id', '=', 'usuarios.id_tipo_ingles')
                ->select(
                    'usuarios.id_user',
                    'usuarios.nombres',
                    'usuarios.apellidos',
                    'usuarios.usuario',
                    'roles.descripcion as nombre_rol',
                    'tipo_ingles.descripcion as tipo_ingles',
                    'evento_agenda_entrenador.id',
                    'evento_agenda_entrenador.start_date',
                    'evento_agenda_entrenador.start_time',
                    'evento_agenda_entrenador.clase_estado',
                    'estados.descripcion_estado'
                )
                ->where('usuarios.estado', 1)
                ->whereNull('usuarios.deleted_at')
                ->where('evento_agenda_entrenador.clase_estado', 10)
                ->whereNull('evento_agenda_entrenador.deleted_at')
                ->whereIn('evento_agenda_entrenador.state', [1,2,3])
                ->orderBy('evento_agenda_entrenador.start_date', 'DESC')
                ->get();
        }
        catch (Exception $e)
        {
            alert()->error("Error', 'An error has occurred, try again, if the problem persists contact support.!");
            return redirect()->to(route('administrador.index'));
        }
    }
}
