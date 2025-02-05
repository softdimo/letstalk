<?php

namespace App\Models\estudiante;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credito extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'creditos';
    protected $primaryKey = 'id_credito';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $fillable = [
       'id_estado',
       'id_estudiante',
       'id_instructor',
       'id_trainer_agenda',
       'paquete',
       'fecha_credito',
       'fecha_consumo_credito',
       'url_checkout_payvalida',
       'id_paquete'
    ];
}
