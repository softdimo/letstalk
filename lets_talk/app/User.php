<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
// use OwenIt\Auditing\Contracts\Auditable;
use App\Models\usuarios\TipoDocumento;

// class User extends Authenticatable
class User extends Model
// class User extends Authenticatable implements Auditable
{
    use Notifiable;
    // use \OwenIt\Auditing\Auditable;

    protected $connection = 'mysql';
    protected $table = 'usuarios';
    protected $primaryKey = 'id_user';
    public $timestamps = true;
    protected $fillable = [
        'usuario',
        'password',
        'nombres',
        'apellidos',
        'numero_documento',
        'id_tipo_documento',
        'id_municipio_nacimiento',
        'fecha_nacimiento',
        'genero',
        'estado',
        'telefono',
        'celular',
        'correo',
        'direccion_residencia',
        'id_municipio_residencia',
        'fecha_ingreso_sistema',
        'id_rol',
        'skype',
        'zoom',
        'zoom_clave',
        'id_nivel',
        'id_tipo_ingles',
        'clave_fallas'
    ];

    protected $hidden = [
        'password'
    ];

    public function documento()
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento', 'id');
    }
}
