<?php

namespace App\Models\usuarios;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $connection = 'mysql';
    protected $table = 'tipo_documento';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
       'descripcion', 'abreviatura'
    ];
}
