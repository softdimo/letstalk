<?php

namespace App\Models\estudiante;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paquete extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'paquetes';
    protected $primaryKey = 'id_paquete';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $fillable = [
       'nombre_paquete',
       'valor_paquete',
       'valor_letras_paquete'
    ];
}
