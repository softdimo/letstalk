<?php

namespace App\Http\Responses\niveles;

use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\usuarios\Nivel;
use App\Traits\FileUploadTrait;

class NivelesStore implements Responsable
{
    use FileUploadTrait;

    public function toResponse($request)
    {
        $msgError = "";
        $nuevoNivel = strtoupper(request('nuevo_crear_nivel', null));
        $validarNivel = Nivel::select('nivel_descripcion')
                                ->where('nivel_descripcion', $nuevoNivel)
                                ->first();

        if ($validarNivel)
        {
            return response()->json("nivel_existe");
        } else
        {
            $baseFileName = "{$nuevoNivel}";
            $carpetaArchivos = '/upfiles/niveles';

            DB::connection('mysql')->beginTransaction();

            try
            {
                $archivoNivel= '';
                if ($request->hasFile('file_crear_nivel'))
                {
                    $archivoNivel = $this->upfileWithName($baseFileName, $carpetaArchivos, $request,
                                                            'file_crear_nivel', 'file_crear_nivel');
                } else {
                    $archivoNivel = null;
                }

                $crearNivel = Nivel::create([
                                    'nivel_descripcion' => $nuevoNivel,
                                    'ruta_pdf_nivel' => $archivoNivel
                                ]);

                if($crearNivel)
                {
                    DB::connection('mysql')->commit();
                    return response()->json("nivel_creado");
                } else
                {
                    DB::connection('mysql')->rollback();
                    $msgError .= "nivel_no_creado";
                }
            } catch (Exception $e) {
                DB::connection('mysql')->rollback();
                $msgError .= "error_exception";
            }
        }

        if(isset($msgError) && !is_null($msgError) &&
            !empty($msgError) && $msgError !== "")
        {
            return response()->json($msgError);
        }
    }
}
