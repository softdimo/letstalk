<?php

namespace App\Models\usuarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserva extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'reservas';
    protected $primaryKey = 'id_reserva';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $fillable = [
       'id_estudiante',
       'id_instructor',
       'id_trainer_horario',
       'link_meet',
       'google_event_id'
    ];
}
