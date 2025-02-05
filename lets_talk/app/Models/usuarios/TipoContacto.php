<?php

namespace App\Models\usuarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoContacto extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'tipo_contacto';
    protected $primaryKey = 'id_tipo_contacto';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $fillable = [
       'tipo_contacto'
    ];
}
