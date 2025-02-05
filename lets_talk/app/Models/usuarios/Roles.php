<?php

namespace App\Models\usuarios;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $connection = 'mysql';
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = true;
    protected $fillable = [
       'descripcion',
       'estado'
    ];
}
