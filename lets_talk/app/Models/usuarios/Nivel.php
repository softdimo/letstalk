<?php

namespace App\Models\usuarios;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'niveles';
    protected $primaryKey = 'id_nivel';
    public $timestamps = true;
    protected $fillable = [
       'nivel_descripcion',
       'ruta_pdf_nivel'
    ];
}
