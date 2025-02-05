<?php

namespace App\Http\Responses\estudiante;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Exception;

class EstudianteShow implements Responsable
{
    public function toResponse($request)
    {
        try {
            $idEstudiante = request('id_estudiante', null);

            $queryEstudiante = DB::table('usuarios')
                ->leftjoin('roles', 'roles.id_rol', '=', 'usuarios.id_rol')
                ->leftjoin('tipo_documento', 'tipo_documento.id', '=', 'usuarios.id_tipo_documento')
                ->leftjoin('niveles', 'niveles.id_nivel', '=', 'usuarios.id_nivel')
                ->select('id_user',
                            DB::raw("CONCAT(nombres, ' ', apellidos) AS nombre_completo"),
                            'usuario',
                            'celular',
                            'roles.descripcion as rol',
                            'usuarios.id_tipo_documento',
                            'tipo_documento.descripcion as tipo_documento',
                            'numero_documento',
                            'correo',
                            'fecha_ingreso_sistema',
                            'nivel_descripcion',
                            'telefono',
                            'fecha_nacimiento',
                            'genero'
                        )
                ->where('usuarios.id_user', $idEstudiante)
                ->where('usuarios.id_rol', 3)
                ->where('usuarios.estado', 1)
                ->whereNull('usuarios.deleted_at')
                ->first();

            return response()->json($queryEstudiante);

        } catch (Exception $e) {
            return response()->josn("error_exception");
        }
    }

    public function resumenEstudiante()
    {
        try {
            return DB::table('usuarios')
                    ->leftjoin('roles', 'roles.id_rol', '=', 'usuarios.id_rol')
                    ->leftjoin('tipo_documento', 'tipo_documento.id', '=', 'usuarios.id_tipo_documento')
                    ->select('id_user',
                                DB::raw("CONCAT(nombres, ' ', apellidos) AS nombre_completo"),
                                'usuario',
                                'celular',
                                'roles.descripcion as rol',
                                'usuarios.id_tipo_documento',
                                'tipo_documento.descripcion as tipo_documento',
                                'numero_documento',
                                'correo',
                                'fecha_ingreso_sistema'
                            )
                    ->where('usuarios.id_rol', 3)
                    ->where('usuarios.estado', 1)
                    ->whereNull('usuarios.deleted_at')
                    ->get();

        } catch (Exception $e) {
            alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
            return redirect()->to(route('home'));
        }
    }
}
