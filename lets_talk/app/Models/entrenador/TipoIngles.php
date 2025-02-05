<?php

namespace App\Models\entrenador;

use Illuminate\Database\Eloquent\Model;

class TipoIngles extends Model
{
    protected $connection = 'mysql';
    protected $table = 'tipo_ingles';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
       'description'
    ];
}
