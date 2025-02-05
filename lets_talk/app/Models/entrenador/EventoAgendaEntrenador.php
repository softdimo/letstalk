<?php

namespace App\Models\entrenador;

use Illuminate\Database\Eloquent\Model;

class EventoAgendaEntrenador extends Model
{
    protected $connection = 'mysql';
    protected $table = 'evento_agenda_entrenador';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
       'title',
       'description',
       'start_date',
       'start_time',
       'end_date',
       'end_time',
       'state',
       'color',
       'id_instructor',
       'id_usuario',
       'id_horario',
       'num_dia',
       'clase_estado'
    ];
}
