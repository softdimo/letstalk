<?php

namespace App\Http\Responses\niveles;

use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\usuarios\Nivel;

class NivelesActivar implements Responsable
{
    public function toResponse($request)
    {
        $idNivel = intval($request->id_nivel);
        DB::connection('mysql')->beginTransaction();

        try {
            $inactivarNivel = DB::table('niveles')
                            ->where('id_nivel', $idNivel)
                            ->update([
                                'deleted_at' => null
                            ]);

            if($inactivarNivel)
            {
                DB::connection('mysql')->commit();
                alert()->success('Successfull Process', 'Level activated');
                return redirect()->to(route('administrador.niveles_index'));
            } else
            {
                DB::connection('mysql')->rollback();
                alert()->error('Error', 'An error has occurred activating the level, please contact support.');
                return redirect()->to(route('administrador.niveles_index'));
            }
        } catch (Exception $e)
        {
            DB::connection('mysql')->rollback();
            alert()->error('Error', 'An error has occurred activating the level, please contact support.');
            return redirect()->to(route('administrador.niveles_index'));
        }
    }
}
