<?php

namespace App\Http\Responses\inicio_sesion;

use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RecoveryPasswordPost implements Responsable
{
    public function toResponse($request)
    {
        $idUser = $request->id_user;
        $newPass = $request->new_pass;
        $confirmNewPass = $request->confirm_new_pass;
        $message = "";

        if ($newPass != $confirmNewPass)
        {
            $message .= "New Password and Confirm New Password must be the same!";
        } else
        {
            DB::connection('mysql')->beginTransaction();

            try
            {
                $userPassUpdate = User::where('id_user', $idUser)
                                    ->update([
                                            'password' => Hash::make($newPass)
                                        ]);

                if($userPassUpdate)
                {
                    DB::connection('mysql')->commit();
                    alert()->success('Successfull Process', 'Password updated correctly.');
                    return view('inicio_sesion.login');

                } else
                {
                    DB::connection('mysql')->rollback();
                   $message .= 'An error occurred updating the password,
                                            try again, if the problem persists contact support.';
                }

            } catch (Exception $e) {
                DB::connection('mysql')->rollback();
                $message .= 'An error occurred updating the password,
                                            try again, if the problem persists contact support.';
            }
        }

        alert()->error('error', $message);
        return back();
    }
}
