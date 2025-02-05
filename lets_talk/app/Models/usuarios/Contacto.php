<?php

namespace App\Models\usuarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacto extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'contactos';
    protected $primaryKey = 'id_contacto';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $fillable = [
       'id_user',
       'id_primer_contacto',
       'primer_telefono',
       'primer_celular',
       'primer_correo',
       'primer_skype',
       'primer_zoom',
       'id_segundo_contacto',
       'segundo_telefono',
       'segundo_celular',
       'segundo_correo',
       'segundo_skype',
       'segundo_zoom',
       'id_opcional_contacto',
       'opcional_telefono',
       'opcional_celular',
       'opcional_correo',
       'opcional_skype',
       'opcional_zoom'
    ];
}
