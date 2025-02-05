<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\administrador\DisponibilidadShow;
use App\Http\Responses\administrador\UsuariosShow;
use App\Http\Responses\administrador\UsuariosStore;
use App\Http\Responses\administrador\UsuariosUpdate;
use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Models\usuarios\Nivel;
use App\Models\usuarios\TipoContacto;
use App\Models\entrenador\DisponibilidadEntrenadores;
use App\Models\entrenador\EventoAgendaEntrenador;
use Illuminate\Support\Facades\DB;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Storage;
use App\Http\Responses\niveles\NivelesStore;
use App\Http\Responses\niveles\NivelesUpdate;
use App\Http\Responses\niveles\NivelesInactivar;
use App\Http\Responses\niveles\NivelesActivar;
use App\Http\Responses\administrador\HorarioStore;
use App\Http\Responses\administrador\HorarioState;
use App\Http\Responses\administrador\DisponibilidadUpdate;
use App\Http\Responses\administrador\DisponibilidadesLibres;
use App\Http\Controllers\DatabaseConnectionController;
use App\Traits\MetodosTrait;
class AdministradorController extends Controller
{
    use MetodosTrait;
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           empty($sesion[3]) || is_null($sesion[3]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $vista = 'administrador.index';
            $checkConnection = $this->checkDatabaseConnection($vista);

            if($checkConnection->getName() == "database_connection") {
                return view('database_connection');
            } else {
                $this->share_data();
                return view($vista);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $vista = 'administrador.create';
            $checkConnection = $this->checkDatabaseConnection($vista);

            if($checkConnection->getName() == "database_connection") {
                return view('database_connection');
            } else {
                $this->share_data();
                return view($vista);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            return new UsuariosStore();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $vista = 'administrador.show';
            $checkConnection = $this->checkDatabaseConnection($vista);

            if($checkConnection->getName() == "database_connection") {
                return view('database_connection');
            } else {
                $usuario = $this->consultarUserEdit($id);
                view()->share('usuario', $usuario);
                $this->share_data();
                return view($vista);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $vista = 'administrador.edit';
            $checkConnection = $this->checkDatabaseConnection($vista);

            if($checkConnection->getName() == "database_connection") {
                return view('database_connection');
            } else {
                $usuario = $this->consultarUserEdit($id);
                view()->share('usuario', $usuario);
                $this->share_data();
                return view($vista);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            return new UsuariosUpdate();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        }
    }

    private function share_data()
    {
        view()->share('usuarios', $this->usuarios());
        view()->share('tipo_documento', $this->tipos_documento());
        view()->share('municipios', $this->municipios());
        view()->share('roles', $this->roles());
        view()->share('niveles', Nivel::orderBy('id_nivel','asc')
                                        ->whereNull('deleted_at')
                                        ->pluck('nivel_descripcion', 'id_nivel'));
        view()->share('disponibilidades', $this->traerDisponibilidades());
        view()->share('tipo_ingles', $this->tipoIngles());
        view()->share('tipo_contacto', TipoContacto::orderBy('tipo_contacto','asc')
                                                    ->pluck('tipo_contacto', 'id_tipo_contacto'));
    }

    public function tipoIngles()
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosShow = new  UsuariosShow();
            return $usuariosShow->tiposIngles();
        }
    }

    public function usuarios()
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosShow = new  UsuariosShow();
            return $usuariosShow->todosLosUsuarios();
        }
    }

    public function tipos_documento()
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosShow = new  UsuariosShow();
           return $usuariosShow->tiposDocumento();
        }
    }

    public function municipios()
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosShow = new  UsuariosShow();
           return $usuariosShow->municipios();
        }
    }

    public function roles()
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosShow = new  UsuariosShow();
           return $usuariosShow->roles();
        }
    }

    public function validarCedula(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosShow = new  UsuariosShow();
           return $usuariosShow->validarDocumento($request);
        }
    }

    public function validarCedulaEdicion(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosShow = new  UsuariosShow();
           return $usuariosShow->validarDocumentoEdicion($request);
        }
    }

    public function validarCorreo(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosShow = new  UsuariosShow();
           return $usuariosShow->validarCorreo($request);
        }
    }

    public function validarCorreoEdicion(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosShow = new  UsuariosShow();
           return $usuariosShow->validarCorreoEdicion($request);
        }
    }

    public function cambiarEstadoUsuario(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosUpd = new UsuariosUpdate();
             return $usuariosUpd->cambiarEstado($request);
        }
    }

    public function actualizarClave(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosUpd = new UsuariosUpdate();
            return $usuariosUpd->cambiarClave($request);
        }
    }

    public function validarVariablesSesion()
    {
        $variables_sesion =[];
        $id_usuario = session('usuario_id');
        array_push($variables_sesion, $id_usuario);
        $username = session('username');
        array_push($variables_sesion, $username);
        $sesion_iniciada = session('sesion_iniciada');
        array_push($variables_sesion, $sesion_iniciada);
        $rol_usuario = session('rol');
        array_push($variables_sesion, $rol_usuario);
        return $variables_sesion;
    }

    public function disponibilidades()
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else
        {
            $vista = 'administrador.disponibilidad_entrenadores';
            $checkConnection = $this->checkDatabaseConnection($vista);

            if($checkConnection->getName() == "database_connection")
            {
                return view('database_connection');
            } else
            {
                $arrayEstados = [];
                $disponibilidades = $this->traerDisponibilidades();

                foreach($disponibilidades as $value)
                {
                    array_push($arrayEstados, $value->state);
                }

                view()->share('arrayEstados', $arrayEstados);
                $this->share_data();
                return view($vista);
            }
        }
    }

    public function traerDisponibilidades()
    {
        try
        {
            $disponibilidadShow = new DisponibilidadShow();
            return $disponibilidadShow->traerDisponibilidades();

        } catch (Exception $e)
        {
            $vista = 'administrador.index';
            $checkConnection = $this->checkDatabaseConnection($vista);

            if($checkConnection->getName() == "database_connection") {
                return view('database_connection');
            } else {
                alert()->error("Ha ocurrido un error!");
                return redirect()->to(route($vista));
            }
        }
    }

    public function vistaAdminDisponibilidad()
    {
        $vista = 'administrador.disponibilidad_admin';
        $checkConnection = $this->checkDatabaseConnection($vista);

        if($checkConnection->getName() == "database_connection")
        {
            return view('database_connection');
        } else
        {
            $todasDisponibilidades = DisponibilidadEntrenadores::select('id_horario', 'horario', 'id_estado')
                                                                ->orderBy('id_horario', 'DESC')
                                                                ->get();
            return view($vista, compact('todasDisponibilidades'));
        }
    }

    public function storeAdminDisponibilidad(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            return new HorarioStore();
        }
    }

    public function changeStateAdminDisponibilidad(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return response()->json('home');
        } else {
            return new HorarioState();
        }
    }

    public function consultarUserEdit($idUser)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $usuariosShow = new UsuariosShow();
            return $usuariosShow->datosEdicionUsuario($idUser);
        }
    }

    public function nivelesIndex()
    {
        $vista = 'administrador.niveles_index';
        $checkConnection = $this->checkDatabaseConnection($vista);

        if($checkConnection->getName() == "database_connection") {
            return view('database_connection');
        } else {
            $niveles = Nivel::select('id_nivel','nivel_descripcion','ruta_pdf_nivel','deleted_at')
                                    ->orderBy('id_nivel', 'DESC')
                                    ->get();

            return view($vista, compact('niveles'));
        }
    }

    public function crearNivel(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return response()->json("to_home");
        } else {
            return new NivelesStore();
        }
    }

    public function editarNivel(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            return new NivelesUpdate();
        }
    }

    public function inactivarNivel(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            return new NivelesInactivar();
        }
    }

    public function activarNivel(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            return new NivelesActivar();
        }
    }

    public function consultarNivel(Request $request)
    {
        $idNivel = intval($request->id_nivel);

        try
        {
            $consultarNivel = Nivel::select('nivel_descripcion')
                                    ->where('id_nivel', $idNivel)
                                    ->first();

            if ($consultarNivel)
            {
                return $consultarNivel;
            } else
            {
                return response()->json('no_consultado');
            }
        } catch (Exception $e) {
            alert()->error('Error', 'An error has occurred consulting the level,
                                    try again, if the problem persists contact support.');
            return back();
        }
    }

    public function actualizarDisponibilidad(Request $request)
    {
        $sesion = $this->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            return new DisponibilidadUpdate();
        }
    }

    public function aboutUs()
    {
        return view('layouts.about_us');
    }

    public function services()
    {
        return view('layouts.services');
    }

    public function disponibilidadesLibres()
    {
        $vista = 'administrador.disponibilidad_libre';
        $checkConnection = $this->checkDatabaseConnection($vista);

        if($checkConnection->getName() == "database_connection")
        {
            return view('database_connection');
        }
        else
        {
            try
            {
                $disponibilidadesLibresObj = new DisponibilidadesLibres();
                $disponibilidadesLibres = $disponibilidadesLibresObj->obtenerDisponibilidadesLibres();

                if ($disponibilidadesLibres !== null)
                {
                    return view($vista, compact('disponibilidadesLibres'));
                }
    
            } catch (Exception $e)
            {
                alert()->error("Error', 'An error has occurred, try again, if the problem persists contact support.!");
                return redirect()->to(route('administrador.index'));
            }
        }
    }
}
