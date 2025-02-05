<?php

namespace App\Http\Responses\inicio_sesion;

use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Hash;
use App\Mail\PasswordRecovery\MailPasswordRecovery;
use Illuminate\Support\Facades\Mail;

class RecoveryPasswordEmail implements Responsable
{
    public function toResponse($request)
    {
        try
        {
            $emailRecovery = $request->pass_recovery;
            $documentRecovery = $request->numero_documento;
    
            $consultaRecoveryPass = User::select('id_user','usuario','correo', 'numero_documento')
                                        ->where('correo', $emailRecovery)
                                        ->where('numero_documento', $documentRecovery)
                                        ->first();
    
            if (isset($consultaRecoveryPass) &&
                !empty($consultaRecoveryPass) && !is_null($consultaRecoveryPass))
            {
                $idUserRecovery = $consultaRecoveryPass->id_user;
                $usuarioRecovery = $consultaRecoveryPass->usuario;
                $correoRecovery = $consultaRecoveryPass->correo;
    
                Mail::to($correoRecovery)
                    ->send(new MailPasswordRecovery($idUserRecovery, $usuarioRecovery, $correoRecovery));
                alert()->info('Info','The recovery password information has been sent to your email.');
                return view('inicio_sesion.login');
            } else
            {
                alert()->error('Error','No records were found in our database with the information entered.');
                return back();
            }
            
        } catch (Exception $e)
        {
            alert()->error('Error', 'An error occurred sending the email,
                                    try again, if the problem persists contact support.');
            return back();
        }
    }
}
