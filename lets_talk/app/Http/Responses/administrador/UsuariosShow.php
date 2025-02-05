<?php

namespace App\Http\Responses\administrador;

use App\Models\usuarios\Roles;
use App\Models\usuarios\Nivel;
use App\Models\usuarios\TipoDocumento;
use App\Models\entrenador\TipoIngles;
use App\User;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;

class UsuariosShow implements Responsable
{
    public function toResponse($request) {}

    public function todosLosUsuarios()
    {
       try
       {
            return DB::table('usuarios')
                            ->join('tipo_documento', 'tipo_documento.id', '=', 'usuarios.id_tipo_documento')
                            ->join('municipios', 'municipios.id_municipio', '=', 'usuarios.id_municipio_nacimiento')
                            ->join('municipios as residencia', 'residencia.id_municipio', '=', 'usuarios.id_municipio_residencia')
                            ->join('roles', 'roles.id_rol', '=', 'usuarios.id_rol')
                            ->leftJoin('niveles', 'niveles.id_nivel', '=', 'usuarios.id_nivel')
                            ->leftJoin('tipo_ingles', 'tipo_ingles.id', '=', 'usuarios.id_tipo_ingles')
                            ->leftJoin('contactos', 'contactos.id_user', '=', 'usuarios.id_user')
                            ->leftJoin('tipo_contacto as tipo_primer_contacto', 'tipo_primer_contacto.id_tipo_contacto', '=', 'contactos.id_primer_contacto')
                            ->leftJoin('tipo_contacto as tipo_segundo_contacto', 'tipo_segundo_contacto.id_tipo_contacto', '=', 'contactos.id_segundo_contacto')
                            ->leftJoin('tipo_contacto as tipo_opcional_contacto', 'tipo_opcional_contacto.id_tipo_contacto', '=', 'contactos.id_opcional_contacto')
                            ->select('usuarios.id_user',
                                     'usuarios.usuario',
                                     'usuarios.nombres',
                                     'usuarios.apellidos',
                                     'usuarios.numero_documento',
                                     'usuarios.fecha_nacimiento',
                                     'usuarios.genero',
                                     'usuarios.estado',
                                     'usuarios.telefono',
                                     'usuarios.celular',
                                     'usuarios.correo',
                                     'usuarios.direccion_residencia',
                                     'usuarios.skype',
                                     'usuarios.zoom',
                                     'usuarios.zoom_clave',
                                     'usuarios.fecha_ingreso_sistema AS fecha_ingreso',
                                     'tipo_documento.descripcion AS tipo_documento',
                                     'municipios.descripcion AS ciudad_nacimiento',
                                     'residencia.descripcion AS ciudad_residencia',
                                     'roles.descripcion AS nombre_rol',
                                     'roles.id_rol',
                                     'niveles.nivel_descripcion AS niveles',
                                     'niveles.id_nivel',
                                     'tipo_ingles.id AS id_tip_ing',
                                     'tipo_ingles.descripcion AS desc_tip_ing',
                                     'tipo_primer_contacto.tipo_contacto AS primer_contacto_tipo',
                                     'contactos.primer_telefono',
                                     'contactos.primer_celular',
                                     'contactos.primer_correo',
                                     'contactos.primer_skype',
                                     'contactos.primer_zoom',
                                     'tipo_segundo_contacto.tipo_contacto AS segundo_contacto_tipo',
                                     'contactos.segundo_telefono',
                                     'contactos.segundo_celular',
                                     'contactos.segundo_correo',
                                     'contactos.segundo_skype',
                                     'contactos.segundo_zoom',
                                     'tipo_opcional_contacto.tipo_contacto AS opcional_contacto_tipo',
                                     'contactos.opcional_telefono',
                                     'contactos.opcional_celular',
                                     'contactos.opcional_correo',
                                     'contactos.opcional_skype',
                                     'contactos.opcional_zoom'
                                    )
                            ->whereNull('usuarios.deleted_at')
                            ->whereNull('tipo_documento.deleted_at')
                            ->whereNull('municipios.deleted_at')
                            ->whereNull('residencia.deleted_at')
                            ->whereNull('roles.deleted_at')
                            ->whereNull('niveles.deleted_at')
                            ->orderBy('usuarios.id_user', 'DESC')
                            ->get()
                            ->toarray();

       } catch (Exception $e)
       {
           alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
           return back();
       }
    }

    public function tiposDocumento()
    {
        try {

            return TipoDocumento::select('id', 'descripcion')
                                            ->get()
                                            ->pluck('descripcion', 'id');
        } catch (Exception $e)
         {
            alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
            return back();
        }
    }

    public function tiposIngles()
    {
        try {

            return TipoIngles::select('id', 'descripcion')
                                            ->get()
                                            ->pluck('descripcion', 'id');

        } catch (Exception $e)
         {
            alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
            return back();
        }
    }

    public function municipios()
    {
        try
        {
           return DB::table('municipios')
                        ->join('departamentos', 'departamentos.id_departamento', '=', 'municipios.id_departamento')
                        ->select(
                                    'municipios.id_municipio',
                                    DB::raw("CONCAT(municipios.descripcion, ' - ', departamentos.descripcion) AS nombre_ciudad")
                                )
                        ->whereNull('municipios.deleted_at')
                        ->where('municipios.estado', 1)
                        ->whereNotNull('municipios.codigo_postal')
                        ->orderBy('municipios.descripcion', 'ASC')
                        ->pluck('nombre_ciudad', 'id_municipio');

        } catch (Exception $e)
         {
            alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
            return back();
        }
    }

    public function roles()
    {
        try {

            return Roles::select('id_rol', 'descripcion')
                                ->where('estado', 1)
                                ->orderBy('descripcion', 'ASC')
                                ->get()
                                ->pluck('descripcion', 'id_rol');

        } catch (Exception $e)
         {
            alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
            return back();
        }
    }

    public function validarDocumento($request)
    {
        $numero_documento = request('numero_documento', null);
        $tipo_documento = request('tipo_documento', null);

        try
        {
            $documento = User::select('numero_documento')
                                ->where('numero_documento', $numero_documento)
                                ->where('id_tipo_documento', $tipo_documento)
                                ->first();

            if(isset($documento) && !empty($documento) && !is_null($documento))
            {
                return response()->json("existe_doc");
            } else {
                return response()->json("no_existe_doc");
            }

        } catch (Exception $e)
        {
            return response()->json("error_exception");
        }
    }

    public function validarDocumentoEdicion($request)
    {
        $numero_documento = request('numero_documento', null);
        $usuario_id = request('id_usuario', session('usuario_id'));
        $tipo_documento_id = request('tipo_documento', null);

        try {

            $documento = User::select('numero_documento')
                                ->where('numero_documento', $numero_documento)
                                ->where('id_tipo_documento', $tipo_documento_id)
                                ->whereNotIn('id_user', array($usuario_id))
                                ->first();

            if(isset($documento) && !empty($documento) && !is_null($documento))
            {
                return response()->json("existe_doc");
            } else {
                return response()->json("no_existe_doc");
            }

        } catch (Exception $e)
        {
            return response()->json("error_exception");
        }
    }

    public function validarCorreo($request)
    {
        $correo = request('email', null);

        try
        {
            $correo = User::select('correo')
                                ->where('correo', $correo)
                                ->first();

            if(isset($correo) && !empty($correo) && !is_null($correo))
            {
                return response()->json("existe_correo");
            } else {
                return response()->json("no_existe_correo");
            }

        } catch (Exception $e)
        {
            return response()->json("error_exception_correo");
        }
    }

    public function validarCorreoEdicion($request)
    {
        $correo = request('email', null);
        $usuario_id = request('id_usuario', session('usuario_id'));

        try {

            $correo = User::select('correo')
                            ->where('correo', $correo)
                            ->whereNotIn('id_user', array($usuario_id))
                            ->first();

            if(isset($correo) && !empty($correo) && !is_null($correo))
            {
                return response()->json("existe_correo");
            } else {
                return response()->json("no_existe_correo");
            }

        } catch (Exception $e)
        {
            return response()->json("error_exception_correo");
        }
    }

    public function consultarCedula($numero_documento, $id_tipo_documento)
    {
        try {

            $cedula = User::where('numero_documento', $numero_documento)
                            ->where('id_tipo_documento', $id_tipo_documento)
                            ->first();

            if(isset($cedula) && !empty($cedula) && !is_null($cedula))
            {
                return $cedula;
            } else {
                return null;
            }

        } catch (Exception $e)
        {
            alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
            return back();
        }
    }

    public function consultarCedula2($numero_documento, $id_user)
    {
        try {

            $cedula = User::where('numero_documento', $numero_documento)
                            ->whereNotIn('id_user', array($id_user))
                            ->get()
                            ->first();

            if(isset($cedula) && !empty($cedula) && !is_null($cedula))
            {
                return $cedula;
            } else {
                return null;
            }

        } catch (Exception $e)
        {
            alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
            return back();
        }
    }

    public function datosEdicionUsuario($idUser)
    {
        try
        {
            return DB::table('usuarios')
            ->join('tipo_documento', 'tipo_documento.id', '=', 'usuarios.id_tipo_documento')
            ->join('municipios', 'municipios.id_municipio', '=', 'usuarios.id_municipio_nacimiento')
            ->join('municipios as residencia', 'residencia.id_municipio', '=', 'usuarios.id_municipio_residencia')
            ->join('roles', 'roles.id_rol', '=', 'usuarios.id_rol')
            ->leftJoin('niveles', 'niveles.id_nivel', '=', 'usuarios.id_nivel')
            ->leftJoin('tipo_ingles', 'tipo_ingles.id', '=', 'usuarios.id_tipo_ingles')
            ->leftJoin('contactos', 'contactos.id_user', '=', 'usuarios.id_user')
            ->leftJoin('tipo_contacto as tipo_primer_contacto', 'tipo_primer_contacto.id_tipo_contacto', '=', 'contactos.id_primer_contacto')
            ->leftJoin('tipo_contacto as tipo_segundo_contacto', 'tipo_segundo_contacto.id_tipo_contacto', '=', 'contactos.id_segundo_contacto')
            ->leftJoin('tipo_contacto as tipo_opcional_contacto', 'tipo_opcional_contacto.id_tipo_contacto', '=', 'contactos.id_opcional_contacto')
            ->select('usuarios.id_user',
                        'usuarios.usuario',
                        'usuarios.nombres',
                        'usuarios.apellidos',
                        'usuarios.id_tipo_documento',
                        'usuarios.numero_documento',
                        'usuarios.id_municipio_nacimiento',
                        'usuarios.fecha_nacimiento',
                        'usuarios.genero',
                        'usuarios.estado',
                        'usuarios.telefono',
                        'usuarios.celular',
                        'usuarios.correo',
                        'usuarios.id_municipio_residencia',
                        'usuarios.direccion_residencia',
                        'usuarios.skype',
                        'usuarios.zoom',
                        'usuarios.zoom_clave',
                        'usuarios.fecha_ingreso_sistema AS fecha_ingreso',
                        'usuarios.id_tipo_ingles',
                        'tipo_documento.descripcion AS tipo_documento',
                        'municipios.descripcion AS ciudad_nacimiento',
                        'residencia.descripcion AS ciudad_residencia',
                        'roles.descripcion AS nombre_rol',
                        'roles.id_rol',
                        'niveles.nivel_descripcion AS niveles',
                        'niveles.id_nivel',
                        'tipo_ingles.id AS id_tip_ing',
                        'tipo_ingles.descripcion AS desc_tip_ing',
                        'tipo_primer_contacto.id_tipo_contacto AS primer_contacto_tipo',
                        'contactos.primer_telefono',
                        'contactos.primer_celular',
                        'contactos.primer_correo',
                        'contactos.primer_skype',
                        'contactos.primer_zoom',
                        'tipo_segundo_contacto.id_tipo_contacto AS segundo_contacto_tipo',
                        'contactos.segundo_telefono',
                        'contactos.segundo_celular',
                        'contactos.segundo_correo',
                        'contactos.segundo_skype',
                        'contactos.segundo_zoom',
                        'tipo_opcional_contacto.id_tipo_contacto AS opcional_contacto_tipo',
                        'contactos.opcional_telefono',
                        'contactos.opcional_celular',
                        'contactos.opcional_correo',
                        'contactos.opcional_skype',
                        'contactos.opcional_zoom'
                    )
            ->where('usuarios.id_user', $idUser)
            ->whereNull('usuarios.deleted_at')
            ->whereNull('tipo_documento.deleted_at')
            ->whereNull('municipios.deleted_at')
            ->whereNull('residencia.deleted_at')
            ->whereNull('roles.deleted_at')
            ->whereNull('niveles.deleted_at')
            ->orderBy('usuarios.id_user', 'DESC')
            ->first();

        } catch (Exception $e) {
            alert()->error('Error', 'An error has occurred, try again, if the problem persists contact support.');
            return back();
        }
    }
}
