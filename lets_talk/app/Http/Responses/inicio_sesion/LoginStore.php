<?php

namespace App\Http\Responses\inicio_sesion;

use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginStore implements Responsable
{
    public function toResponse($request)
    {
        try
        {
            DB::connection()->getPDO();
	        DB::connection()->getDatabaseName();

            $username = request('username', null);
            $pass = request('pass', null);

            if(!isset($username) || empty($username) || is_null($username) ||
               !isset($pass) || empty($pass) || is_null($pass))
            {
                alert()->error('Error','Username and Password are required!');
                return back();
            }

            $user = $this->consultarUsuario($username);

            if(isset($user) && !empty($user) && !is_null($user))
            {
                $cont_clave_erronea = $user->clave_fallas;

                if($user->clave_fallas >= 4)
                {
                    $this->inactivarUsuario($user->id_user);
                }

                if($user->estado == 6 || $user->estado != 1)
                {
                    alert()->error('Error',
                                    'Username ' . $username . ' is locked,
                                    please contact the administrator to unlock it.');
                    return back();
                }

                if(Hash::check($pass, $user->password))
                {
                    // Rol entrenador
                    if($user->id_rol == 1 || $user->id_rol == "1")
                    {
                        // Creamos las variables de sesion
                        $this->crearVariablesSesion($user);
                        return redirect()->to(route('trainer.create'));

                       // Rol Estudiante
                    } elseif($user->id_rol == 3 || $user->id_rol == "3")
                    {
                         // Creamos las variables de sesion
                         $this->crearVariablesSesion($user);
                         return redirect()->to(route('estudiante.index'));

                    } // Rol Administrador
                    elseif($user->id_rol == 2 || $user->id_rol == "2")
                    {
                        // Creamos las variables de sesion
                        $this->crearVariablesSesion($user);
                        return redirect()->to(route('administrador.index'));
                    } else {

                        // Si el rol es diferente a los mencionados, mostramos mensaje
                        alert()->error('Error','Username ' . $username . ' has an invalid role!');
                        return back();
                    }

                } else {
                    $cont_clave_erronea += 1;
                    $this->actualizarClaveFallas($user->id_user, $cont_clave_erronea);
                    alert()->error('Error','Invalid Credentials');
                    return back();
                }

            } else {
                alert()->error('Error','No records were found for the username ' . $username);
                return back();
            }

        } catch (Exception $e)
        {
            alert()->error('Error', 'An error has occurred,
                            try again, if the problem persists contact support.');
            return back();
        }
    }

    private function crearVariablesSesion($user)
    {
        // Creamos las variables de sesion
        session()->put('usuario_id', $user->id_user);
        session()->put('username', $user->usuario);
        session()->put('sesion_iniciada', true);
        session()->put('rol', $user->id_rol);
    }

    private function consultarUsuario($username)
    {
        try {
            return User::where('usuario', $username)
                        ->whereNull('deleted_at')
                        ->first();

        } catch (Exception $e)
        {
            alert()->error('Error','An error has occurred,
                            try again, if the problem persists contact support.');
            return back();
        }
    }

    private function inactivarUsuario($id_user)
    {
        try {

            $user = User::find($id_user);
            $user->estado = 6;
            $user->save();

        } catch (Exception $e)
        {
            alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
            return back();
        }
    }

    private function actualizarClaveFallas($usuario_id, $contador)
    {
        try {
            $user = User::find($usuario_id);
            $user->clave_fallas = $contador;
            $user->save();

        } catch (Exception $e) {
            alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
            return back();
        }
    }
}
