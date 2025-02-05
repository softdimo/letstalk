<?php

namespace App\Http\Controllers\estudiante;

use App\Http\Controllers\admin\AdministradorController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\MetodosTrait;
use App\Models\entrenador\DisponibilidadEntrenadores;
use App\Models\entrenador\EventoAgendaEntrenador;
use App\Models\estudiante\Paquete;
use App\Http\Responses\administrador\DisponibilidadShow;
use App\Http\Responses\estudiante\ReservarClase;
use App\Http\Responses\estudiante\CancelarClase;
use App\Http\Responses\estudiante\ComprarCreditos;
use App\Models\estudiante\Credito;
use App\Http\Responses\estudiante\CreditosShow;
use App\Models\usuarios\Reserva;
use Exception;
use Illuminate\Support\Facades\DB;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class EstudianteController extends Controller
{
    use MetodosTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adminCtrl = new AdministradorController();
        $sesion = $adminCtrl->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           empty($sesion[3]) || is_null($sesion[3]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $vista = 'estudiante.index';
            $checkConnection = $this->checkDatabaseConnection($vista);

            if($checkConnection->getName() == "database_connection") {
                return view('database_connection');
            } else {
                $idEstudiante = session('usuario_id');
                $misSesiones = $this->misSesiones($idEstudiante);

                return view($vista, compact('misSesiones'));
            }
        }
    }

    // ==============================================================
    // ==============================================================

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    // ==============================================================
    // ==============================================================

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    // ==============================================================
    // ==============================================================

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    // ==============================================================
    // ==============================================================

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    // ==============================================================
    // ==============================================================

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    // ==============================================================
    // ==============================================================

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    // ==============================================================
    // ==============================================================

    private function shareData()
    {
        $paquetes = Paquete::orderBy('nombre_paquete', 'asc')->get()->mapWithKeys(function ($paquete) {
            return [$paquete->id_paquete => $paquete->nombre_paquete . ' - ' .$paquete->valor_letras_paquete];
        });
    
        view()->share('paquetes', $paquetes);
    }
    
    // ==============================================================
    // ==============================================================

    public function disponibilidadEntrenadores()
    {
        $adminCtrl = new AdministradorController();
        $sesion = $adminCtrl->validarVariablesSesion();

        if(empty($sesion[0]) || is_null($sesion[0]) &&
           empty($sesion[1]) || is_null($sesion[1]) &&
           empty($sesion[2]) || is_null($sesion[2]) &&
           empty($sesion[3]) || is_null($sesion[3]) &&
           $sesion[2])
        {
            return redirect()->to(route('home'));
        } else {
            $vista = 'estudiante.disponibilidad';
            $checkConnection = $this->checkDatabaseConnection($vista);

            if($checkConnection->getName() == "database_connection") {
                return view('database_connection');
            } else
            {
                $idEstudiante = session('usuario_id');

                $diaHoy = Carbon::now()->format('Y-m-d');
                $horaAhora = Carbon::now();

                $disponibilidadEntrenadores = EventoAgendaEntrenador::leftJoin('usuarios', 'usuarios.id_user', '=', 'evento_agenda_entrenador.id_instructor')
                    ->leftJoin('creditos', function($join) use ($idEstudiante) {
                        $join->on('creditos.id_trainer_agenda', '=', 'evento_agenda_entrenador.id')
                            ->where(function($query) use ($idEstudiante) {
                                $query->where('creditos.id_estudiante', $idEstudiante)
                                    ->orWhereNull('creditos.id_estado');
                            });
                    })
                    ->leftJoin('reservas', 'reservas.id_trainer_horario', '=', 'evento_agenda_entrenador.id')
                    ->select(
                        'evento_agenda_entrenador.id as id_evento',
                        'evento_agenda_entrenador.id_instructor',
                        DB::raw("CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo"),
                        'evento_agenda_entrenador.start_date',
                        'evento_agenda_entrenador.start_time',
                        'evento_agenda_entrenador.end_date',
                        'evento_agenda_entrenador.end_time',
                        DB::raw('COALESCE(creditos.id_estado, 7) AS id_estado'),
                        'creditos.id_estudiante',
                        'link_meet',
                        'google_event_id'
                    )
                    ->where(function ($query) {
                        $query->whereNull('creditos.id_estado')
                            ->orWhereIn('creditos.id_estado', [7, 8]);
                    })
                    ->where('evento_agenda_entrenador.clase_estado', 10)
                    ->where('evento_agenda_entrenador.state', 1)
                    ->where('evento_agenda_entrenador.start_date', '>=',  $diaHoy)
                    ->where(DB::raw("CONCAT(evento_agenda_entrenador.start_date, ' ', evento_agenda_entrenador.start_time)"), '>=', $horaAhora->copy()->addHours(2)->format('Y-m-d H:i:s'))
                    ->orderBy('evento_agenda_entrenador.start_date', 'desc')
                    ->orderBy('evento_agenda_entrenador.start_time', 'desc')
                    ->get();

                return view($vista, compact('disponibilidadEntrenadores'));
            }
        }
    }

    // ==============================================================
    // ==============================================================

    public function misCreditos(Request $request)
    {
        try
        {
            $adminCtrl = new AdministradorController();
            $sesion = $adminCtrl->validarVariablesSesion();
    
            if(
                empty($sesion[0]) || is_null($sesion[0]) &&
                empty($sesion[1]) || is_null($sesion[1]) &&
                empty($sesion[2]) || is_null($sesion[2]) &&
                empty($sesion[3]) || is_null($sesion[3]) &&
                $sesion[2]
               )
            {
                return redirect()->to(route('home'));
            }
            else
            {
                $vista = 'estudiante.mis_creditos';
                $checkConnection = $this->checkDatabaseConnection($vista);

                if($checkConnection->getName() == "database_connection") {
                    return view('database_connection');
                } else
                {
                    $idEstudiante = session('usuario_id');
                    $creditosShow = new CreditosShow();
                    $misCreditos = $creditosShow->toResponse($idEstudiante);
                    $totalCreditosDisponibles =  $creditosShow->totalCreditosDisponibles($idEstudiante);
                    $misSesiones =$creditosShow->sesionesEstudiante($idEstudiante);

                    if($misCreditos == "error_exception" || $totalCreditosDisponibles == "error_exception" ||
                        $misSesiones == "error_exception")
                    {
                        alert()->error("Error", "Ha ocurrido un error de base de datos, íntente de nuevo!");
                        return redirect()->to(route('estudiante.mis_creditos'));
                    }

                    $this->shareData();

                    if(isset($_GET['order']) && !is_null($_GET['order']))
                    {
                        alert()->success("Proceso Exitoso", "Los créditos han sido comprados correctamente!");
                        return redirect()->to(route('estudiante.mis_creditos'));

                    } else
                    {
                        return view($vista, compact('misCreditos', 'totalCreditosDisponibles', 'misSesiones'));
                    }
                }
            }

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function creditosDisponibles(Request $request)
    {
        try {
            $adminCtrl = new AdministradorController();
            $sesion = $adminCtrl->validarVariablesSesion();
    
            if(empty($sesion[0]) || is_null($sesion[0]) &&
               empty($sesion[1]) || is_null($sesion[1]) &&
               empty($sesion[2]) || is_null($sesion[2]) &&
               empty($sesion[3]) || is_null($sesion[3]) &&
               $sesion[2]
            )
            {
                return redirect()->to(route('home'));
            } else
            {
                return view('estudiante.creditos_disponibles');
            }
        } catch (Exception $e) {
            return response()->json("error_exception");
        }
    }

    public function comprarCreditos(Request $request)
    {
        try {
            $adminCtrl = new AdministradorController();
            $sesion = $adminCtrl->validarVariablesSesion();
    
            if(empty($sesion[0]) || is_null($sesion[0]) &&
               empty($sesion[1]) || is_null($sesion[1]) &&
               empty($sesion[2]) || is_null($sesion[2]) &&
               empty($sesion[3]) || is_null($sesion[3]) &&
               $sesion[2]
            )
            {
                return redirect()->to(route('home'));
            } else {
                return new ComprarCreditos();
            }

        } catch (Exception $e)
        {
            alert()->error("Error", "Ha ocurrido un error, íntente de nuevo, si el problema persiste, contácte con soporte!");
        }
    }

    public function misSesiones($idEstudiante)
    {
        try {
            $adminCtrl = new AdministradorController();
            $sesion = $adminCtrl->validarVariablesSesion();
    
            if(empty($sesion[0]) || is_null($sesion[0]) &&
               empty($sesion[1]) || is_null($sesion[1]) &&
               empty($sesion[2]) || is_null($sesion[2]) &&
               empty($sesion[3]) || is_null($sesion[3]) &&
               $sesion[2]
            )
            {
                return redirect()->to(route('home'));
            } else
            {
                return Reserva::leftjoin('evento_agenda_entrenador','evento_agenda_entrenador.id','=','reservas.id_trainer_horario')
                ->leftjoin('usuarios as instructor','instructor.id_user','=','reservas.id_instructor')
                ->select(
                    'reservas.id_estudiante',
                    'reservas.id_instructor',
                    DB::raw("CONCAT(instructor.nombres, ' ', instructor.apellidos) AS nombre_instructor"),
                    'reservas.id_trainer_horario',
                    'start_date',
                    'start_time',
                    'link_meet'
                )
                ->where('id_estudiante', $idEstudiante)
                ->orderBy('start_date', 'desc')
                ->orderBy('start_time', 'desc')
                ->get();
            }
        } catch (Exception $e) {
            return response()->json("error_exception");
        }
    }
        
    public function reservarClase(Request $request)
    {
        try
        {
            $adminCtrl = new AdministradorController();
            $sesion = $adminCtrl->validarVariablesSesion();
    
            if(empty($sesion[0]) || is_null($sesion[0]) &&
               empty($sesion[1]) || is_null($sesion[1]) &&
               empty($sesion[2]) || is_null($sesion[2]) &&
               empty($sesion[3]) || is_null($sesion[3]) &&
               $sesion[2]
            )
            {
                return redirect()->to(route('home'));
                } else {
                return new ReservarClase();
            }
        } catch (Exception $e) {
            return response()->json("error_exception");
        }
    }

    public function getGoogleClient()
    {
        try {
            $adminCtrl = new AdministradorController();
            $sesion = $adminCtrl->validarVariablesSesion();
    
            if(empty($sesion[0]) || is_null($sesion[0]) &&
               empty($sesion[1]) || is_null($sesion[1]) &&
               empty($sesion[2]) || is_null($sesion[2]) &&
               empty($sesion[3]) || is_null($sesion[3]) &&
               $sesion[2]
            )
            {
                return redirect()->to(route('home'));
            } else
            {
                $reservarClase = new ReservarClase();
                return $reservarClase->getGoogleClient();
            }
        } catch (Exception $e)
        {
            return response()->json("error_exception");
        }
    }

    public function redirectToGoogle()
    {
        try {
            $adminCtrl = new AdministradorController();
            $sesion = $adminCtrl->validarVariablesSesion();
    
            if(empty($sesion[0]) || is_null($sesion[0]) &&
               empty($sesion[1]) || is_null($sesion[1]) &&
               empty($sesion[2]) || is_null($sesion[2]) &&
               empty($sesion[3]) || is_null($sesion[3]) &&
               $sesion[2]
            )
            {
                return redirect()->to(route('home'));
            } else
            {
                $redirectToGoogle = new ReservarClase();
                return $redirectToGoogle->redirectToGoogle();
            }
        } catch (Exception $e)
        {
            return response()->json("error_exception");
        }
    }

    public function handleGoogleCallbackReservar(Request $request)
    {
        try {
            $adminCtrl = new AdministradorController();
            $sesion = $adminCtrl->validarVariablesSesion();
    
            if(empty($sesion[0]) || is_null($sesion[0]) &&
               empty($sesion[1]) || is_null($sesion[1]) &&
               empty($sesion[2]) || is_null($sesion[2]) &&
               empty($sesion[3]) || is_null($sesion[3]) &&
               $sesion[2]
            )
            {
                return redirect()->to(route('home'));
            } else
            {
                $handleGoogleCallbackReservar = new ReservarClase();
                return $handleGoogleCallbackReservar->handleGoogleCallbackReservar($request);
            }
        } catch (Exception $e)
        {
            return response()->json("error_exception");
        }
    }

    public function handleGoogleCallbackCancelar(Request $request)
    {
        try {
            $adminCtrl = new AdministradorController();
            $sesion = $adminCtrl->validarVariablesSesion();
    
            if(empty($sesion[0]) || is_null($sesion[0]) &&
               empty($sesion[1]) || is_null($sesion[1]) &&
               empty($sesion[2]) || is_null($sesion[2]) &&
               empty($sesion[3]) || is_null($sesion[3]) &&
               $sesion[2]
            )
            {
                return redirect()->to(route('home'));
            } else
            {
                $handleGoogleCallbackCancelar = new CancelarClase();
                return $handleGoogleCallbackCancelar->handleGoogleCallbackCancelar($request);
            }
        } catch (Exception $e)
        {
            return response()->json("error_exception");
        }
    }

    public function createMeet()
    {
        try {
            $adminCtrl = new AdministradorController();
            $sesion = $adminCtrl->validarVariablesSesion();
    
            if(empty($sesion[0]) || is_null($sesion[0]) &&
               empty($sesion[1]) || is_null($sesion[1]) &&
               empty($sesion[2]) || is_null($sesion[2]) &&
               empty($sesion[3]) || is_null($sesion[3]) &&
               $sesion[2]
            )
            {
                return redirect()->to(route('home'));
            } else
            {
                $createMeet = new ReservarClase();
                return $createMeet->createMeet();
            }
        } catch (Exception $e)
        {
            return response()->json("error_exception");
        }
    }

    public function cancelarClase(Request $request)
    {
        try {
            $adminCtrl = new AdministradorController();
            $sesion = $adminCtrl->validarVariablesSesion();
    
            if(empty($sesion[0]) || is_null($sesion[0]) &&
               empty($sesion[1]) || is_null($sesion[1]) &&
               empty($sesion[2]) || is_null($sesion[2]) &&
               empty($sesion[3]) || is_null($sesion[3]) &&
               $sesion[2]
            )
            {
                return redirect()->to(route('home'));
            } else
            {
                return new CancelarClase();
            }
        } catch (Exception $e)
        {
            return response()->json("error_exception");
        }
    }
} // FIN Class EstudianteController
