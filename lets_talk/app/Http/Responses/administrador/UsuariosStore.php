<?php

namespace App\Http\Responses\administrador;

use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\administrador\UsuariosShow;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\MetodosTrait;

class UsuariosStore implements Responsable
{
    use MetodosTrait;
    
    public function toResponse($request)
    {
        $usuarioShow = new UsuariosShow();
        $nombres = request('nombres', null);
        $apellidos = request('apellidos', null);
        $id_tipo_documento = request('id_tipo_documento', null);
        $numero_documento = request('numero_documento', null);
        $id_municipio_nacimiento = request('id_municipio_nacimiento', null);
        $fecha_nacimiento = request('fecha_nacimiento', null);
        $genero = request('genero', null);
        $estado = request('estado', null);
        $telefono = request('telefono', null);
        $celular = request('celular', null);
        $correo = request('correo', null);
        $direccion_residencia = request('direccion_residencia', null);
        $id_municipio_residencia = request('id_municipio_residencia', null);
        $id_rol = request('id_rol', null);
        $id_nivel = request('id_nivel', null);
        $id_tipo_ingles = request('id_tipo_ingles', null);
        $msgError = "";

        if(strlen($numero_documento) < 6)
        {
            alert()->error('Info', 'The document number must be at least 6 characters long.');
            return back();
        }
        
        if(isset($id_rol) && $id_rol == 3)
        {
            $nivel_ingles = $id_nivel;
            $tipo_ingles = null;
        } else
        {
            $nivel_ingles = null;
            $tipo_ingles = $id_tipo_ingles;
        }

        // Consultamos si ya existe un usuario con la cedula ingresada
        $consulta_cedula = $usuarioShow->consultarCedula($numero_documento, $id_tipo_documento);

        if(isset($consulta_cedula) && !empty($consulta_cedula) &&
           !is_null($consulta_cedula))
        {
            $msgError .= "The document number already exists.";
        } else
        {
            // Contruimos el nombre de usuario
            $separar_apellidos = explode(" ", $apellidos);
            $usuario = substr($this->quitarCaracteresEspeciales(trim($nombres)), 0,1) .
                                trim($this->quitarCaracteresEspeciales($separar_apellidos[0]));
            $usuario = preg_replace("/(Ñ|ñ)/", "n", $usuario);
            $usuario = strtolower($usuario);
            $complemento = "";

            while($this->consultaUsuario($usuario.$complemento))
            {
                $complemento++;
            }

            $fecha_nacimiento = strtotime($fecha_nacimiento);
            $fecha_ingreso_sistema = Carbon::now()->timestamp;

            DB::connection('mysql')->beginTransaction();

            try {
                $nuevo_usuario = User::create([
                    'usuario' => $usuario.$complemento,
                    'password' => Hash::make($numero_documento),
                    'nombres' => strtoupper($nombres),
                    'apellidos' => strtoupper($apellidos),
                    'numero_documento' => $numero_documento,
                    'id_tipo_documento' => $id_tipo_documento,
                    'id_municipio_nacimiento' => $id_municipio_nacimiento,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'genero' => $genero,
                    'estado' => $estado,
                    'telefono' => $telefono,
                    'celular' => $celular,
                    'correo' => $correo,
                    'direccion_residencia' => $direccion_residencia,
                    'id_municipio_residencia' => $id_municipio_residencia,
                    'fecha_ingreso_sistema' => $fecha_ingreso_sistema,
                    'id_rol' => $id_rol,
                    'id_nivel' => $nivel_ingles,
                    'id_tipo_ingles' => $tipo_ingles,
                    'clave_fallas' => 0
                ]);

                if($nuevo_usuario)
                {
                    DB::connection('mysql')->commit();
                    alert()->success('Successfull Process', 'User successfully created, the user name is: '
                                        . $nuevo_usuario->usuario . ' and the password is you document number');
                    return redirect()->to(route('administrador.index'));

                } else
                {
                    DB::connection('mysql')->rollback();
                    $msgError .= 'An error has occurred creating the user, please contact support.';
                }

            } catch (Exception $e)
            {
                DB::connection('mysql')->rollback();
                $msgError .= 'An error has occurred creating the user, try again,
                                        if the problem persists contact support.';
            }
        }

        if(isset($msgError) && !is_null($msgError) &&
            !empty($msgError) && $msgError != "")
        {
            alert()->error('Error', $msgError);
            return back();
        }
    }

    private function consultaUsuario($usuario)
    {
        try
        {

            return User::where('usuario', $usuario)
                            ->first();
        } catch (Exception $e)
        {
            alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
            return back();
        }
    }
}
