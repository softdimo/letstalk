<?php

namespace App\Http\Responses\niveles;

use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\usuarios\Nivel;

class NivelesInactivar implements Responsable
{
    public function toResponse($request)
    {
        $idNivel = intval($request->id_nivel);
        $fechaActual = now();

        DB::connection('mysql')->beginTransaction();

        try {
            $inactivarNivel = DB::table('niveles')
                            ->where('id_nivel', $idNivel)
                            ->update([
                                'deleted_at' => $fechaActual
                            ]);

            if($inactivarNivel)
            {
                DB::connection('mysql')->commit();

                // NUEVA CONSULTA
                $queryUsuariosNivel = DB::table('usuarios')
                                    ->join('niveles', 'niveles.id_nivel', '=', 'usuarios.id_nivel')
                                    ->select('usuarios.id_user')
                                    ->whereNotNull('niveles.deleted_at')
                                    ->get();
                
                foreach ($queryUsuariosNivel as $idNivel)
                {
                    $idUser = $idNivel->id_user;

                    DB::table('usuarios')
                            ->where('id_user', $idUser)
                            ->update([
                                'id_nivel' => 0
                            ]);
                }

                alert()->success('Successfull Process', 'Level inactivated');
                return redirect()->to(route('administrador.niveles_index'));
            } else
            {
                DB::connection('mysql')->rollback();
                alert()->error('Error', 'An error has occurred inactivating the level, please contact support.');
                return redirect()->to(route('administrador.niveles_index'));
            }
        } catch (Exception $e)
        {
            DB::connection('mysql')->rollback();
            alert()->error('Error', 'An error has occurred inactivating the level, please contact support.');
            return redirect()->to(route('administrador.niveles_index'));
        }
    }
}
