<?php

namespace App\Http\Responses\estudiante;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Exception;
use App\User;
use App\Models\usuarios\Reserva;
use App\Models\estudiante\Credito;
use App\Models\entrenador\EventoAgendaEntrenador;
use Carbon\Carbon;
use App\Http\Controllers\estudiante\EstudianteController;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Reservas\MailReservaClase;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class ReservarClase implements Responsable
{
    public function toResponse($request)
    {
        $idEstudiante =  session('usuario_id');
        $idInstructor = intval(request('id_instructor', null));
        $idHorario = intval(request('id_horario', null));
        $fechaClase = request('fecha_clase', null);
        $horaClaseInicio = request('hora_clase_inicio', null);

        // ======================================================

        // Guardar los detalles de la reserva en la sesión
        Session::put('reservation_details', [
            'id_estudiante' => $idEstudiante,
            'id_instructor' => $idInstructor,
            'id_horario' => $idHorario,
            'fecha_clase' => $fechaClase,
            'hora_clase_inicio' => $horaClaseInicio,
        ]);

        // ======================================================

        $queryDisponibilidadCreditos = Credito::select('id_credito', 'paquete')
                                    ->where('id_estado', 7)
                                    ->where('id_estudiante',$idEstudiante)
                                    ->orderBy('id_credito','asc')
                                    ->first();

        if (isset($queryDisponibilidadCreditos) && !is_null($queryDisponibilidadCreditos) && !empty($queryDisponibilidadCreditos))
        {

            if (!Session::has('google_access_token'))
            {
                $authUrl = $this->getGoogleClient()->createAuthUrl();
                return response()->json(['status' => 'auth_required', 'auth_url' => $authUrl]);
            }

            return $this->processReservation();

        } else {
            DB::connection('mysql')->rollback();
            return response()->json(['status' => 'creditos_no_disponibles']);
        }
    } // FIN toResponse

    // ==============================================================
    // ==============================================================
    // ==============================================================
    // ==============================================================
    // ==============================================================

    public function processReservation()
    {
        $reservationDetails = Session::get('reservation_details');

        if (!$reservationDetails) {
            return response()->json(['status' => 'error']);
        }

        $idEstudiante = $reservationDetails['id_estudiante'];
        $idInstructor = $reservationDetails['id_instructor'];
        $idHorario = $reservationDetails['id_horario'];
        $fechaClase = $reservationDetails['fecha_clase'];
        $horaClaseInicio = $reservationDetails['hora_clase_inicio'];

        $queryDisponibilidadCreditos = Credito::select('id_credito', 'paquete')
            ->where('id_estado', 7)
            ->where('id_estudiante', $idEstudiante)
            ->orderBy('id_credito', 'asc')
            ->first();

        if (isset($queryDisponibilidadCreditos) && !is_null($queryDisponibilidadCreditos) && !empty($queryDisponibilidadCreditos))
        {
            try {
                $createLinkMeet = $this->createMeet($fechaClase, $horaClaseInicio);

                if (isset($createLinkMeet) && !is_null($createLinkMeet) && !empty($createLinkMeet)) {
                    DB::connection('mysql')->beginTransaction();

                    $reservarClaseCreate = Reserva::create([
                        'id_estudiante' => $idEstudiante,
                        'id_instructor' => $idInstructor,
                        'id_trainer_horario' => $idHorario,
                        'link_meet' => $createLinkMeet['eventLink'],
                        'google_event_id' => $createLinkMeet['eventId'],
                    ]);

                    if (isset($reservarClaseCreate) && !is_null($reservarClaseCreate) && !empty($reservarClaseCreate)) {
                        DB::connection('mysql')->commit();

                        $queryEventoAgendaEntrenador = EventoAgendaEntrenador::select('id', 'start_date', 'start_time')
                            ->where('id', $idHorario)
                            ->first();

                        $fechaClase = $queryEventoAgendaEntrenador->start_date;
                        $horaClase = $queryEventoAgendaEntrenador->start_time;
                        $fechaHora = Carbon::createFromFormat('Y-m-d H:i', $fechaClase . ' ' . $horaClase)->timestamp;

                        $idCredito = $queryDisponibilidadCreditos->id_credito;
                        Credito::where('id_credito', $idCredito)
                            ->update([
                                'id_estado' => 8,
                                'id_instructor' => $idInstructor,
                                'id_trainer_agenda' => $idHorario,
                                'fecha_consumo_credito' => $fechaHora,
                            ]);

                        DB::connection('mysql')->commit();

                        EventoAgendaEntrenador::where('id', $idHorario)
                            ->update([
                                'clase_estado' => 9,
                                'color' => '#dc3545',
                            ]);

                        DB::connection('mysql')->commit();

                        $this->enviarCorreoReservaClase($idEstudiante, $idInstructor, $idHorario);

                        // Reiniciar la sesión
                        Session::forget('google_access_token');
                        Session::forget('reservation_details');

                        return 'clase_reservada';
                    }
                }
            } catch (Exception $e) {
                DB::connection('mysql')->rollback();
                return response()->json(['status' => 'error']);
            }
        } else {
            return response()->json(['status' => 'creditos_no_disponibles']);
        }
    }
    
    // ==============================================================
    // ==============================================================
    // ==============================================================
    // ==============================================================
    // ==============================================================

    /*
        Configura y devuelve una instancia de Google_Client con las credenciales y configuraciones necesarias para interactuar con las APIs de Google.
        1. Crea una nueva instancia de Google_Client.
        2. Configura el client_id, client_secret y redirect_uri usando las variables de entorno (env()).
        3. Establece el alcance necesario para acceder a los eventos del calendario (Google_Service_Calendar::CALENDAR_EVENTS).
        4. Configura el tipo de acceso como offline para obtener un token de actualización.
        5. Establece el prompt como consent para asegurarse de que se solicita el consentimiento del usuario cada vez que se autentica.
        3. Devuelve la instancia de Google_Client configurada.
    */

    public function getGoogleClient()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI_RESERVAR'));
        $client->setScopes([Google_Service_Calendar::CALENDAR_EVENTS]);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        // Ignorar verificación de SSL solo para desarrollo local
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));

        // Ó Usar el archivo de certificados CA
        // $client->setHttpClient(new \GuzzleHttp\Client(['verify' => 'ruta/a/tu/cacert.pem']));

        return $client;
    }

    // ==============================================================
    // ==============================================================

    /*
        Redirige al usuario a la página de inicio de sesión de Google para autenticarse y autorizar a tu aplicación a acceder a sus datos de Google Calendar.
        1. Obtiene una instancia de Google_Client usando el método getGoogleClient().
        2. Llama a createAuthUrl() en el cliente de Google, que genera la URL de autenticación de Google.
        3. Redirige al usuario a esta URL, donde Google le pedirá que inicie sesión y autorice a tu aplicación.
    */
    public function redirectToGoogle()
    {
        $client = $this->getGoogleClient();
        $authUrl = $client->createAuthUrl();
        return redirect()->away($authUrl);
    }

    // ==============================================================
    // ==============================================================

    /*
        Maneja la redirección de vuelta desde Google después de que el usuario haya autenticado y autorizado la aplicación. Intercambia el código de autorización por un token de acceso y lo guarda en la sesión.
        1. Obtiene una instancia de Google_Client usando el método getGoogleClient().
        2. Llama a authenticate() en el cliente de Google con el código de autorización recibido en la solicitud (obtenido de request->input('code')).
        3. Obtiene el token de acceso llamando a getAccessToken().
        4. Guarda el token de acceso en la sesión usando Session::put('google_access_token', $accessToken).
        5. Redirige al usuario a la ruta createMeet para crear una reunión en Google Meet.
    */

    public function handleGoogleCallbackReservar(Request $request)
    {
        $client = $this->getGoogleClient();

        if ($request->has('code'))
        {
            $client->authenticate($request->get('code'));
            $accessToken = $client->getAccessToken();
            Session::put('google_access_token', $accessToken);

            // Verificar si el token se ha almacenado correctamente

            if (Session::has('google_access_token'))
            {
                $reservationStatus = $this->processReservation();

                if ($reservationStatus === "clase_reservada")
                {
                    alert()->success('Clase Reservada', 'Puedes cancelar de no poder asistir');
                    return redirect()->route('estudiante.index');

                }
                else
                {
                    alert()->success('Error', 'Clase no reservada');
                    return redirect()->route('estudiante.disponibilidad');
                }
            } else
            {
                alert()->success('Error', 'Falla en el almacenamiento del Access Token');
                return redirect()->route('estudiante.disponibilidad');
            }
        }
    }

    // ==============================================================
    // ==============================================================

    /*
        Crea una nueva reunión en Google Meet y proporciona el enlace para unirse a la reunión.
        1. Obtiene una instancia de Google_Client usando el método getGoogleClient().
        2. Establece el token de acceso en el cliente de Google usando setAccessToken() con el token guardado en la sesión.
        3. Crea una instancia de Google_Service_Calendar con el cliente autenticado.
        4. Define un evento de Google Calendar, incluyendo los detalles de la reunión (resumen, hora de inicio y fin, y datos de la conferencia para crear una reunión de Google Meet).
        5. Inserta el evento en el calendario primario (primary) usando events->insert().
        6. Imprime el enlace para unirse a la reunión (getHangoutLink()).
    */

    public function createMeet($fechaClase, $horaClaseInicio)
    {
        $client = $this->getGoogleClient();
        $client->setAccessToken(Session::get('google_access_token'));
        $service = new Google_Service_Calendar($client);
        
        // Crear la fecha y hora de inicio y fin del evento
        $startDateTime = $fechaClase . 'T' . $horaClaseInicio . ':00-05:00';
        $endDateTime = Carbon::parse($startDateTime)->addMinutes(30)->format('Y-m-d\TH:i:sP');

        $event = new Google_Service_Calendar_Event([
            'summary' => 'Clase LetsTalk',
            'start' => ['dateTime' => $startDateTime],
            'end' => ['dateTime' => $endDateTime],
            'conferenceData' => [
                'createRequest' => [
                    'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                    'requestId' => 'some-random-string'
                ]
            ]
        ]);

        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);

        $eventId = $event->id;
        $eventLink = $event->getHangoutLink();

        return [
            'eventId' => $eventId,
            'eventLink' => $eventLink
        ];
    }

    // ==============================================================
    // ==============================================================

    public function enviarCorreoReservaClase($idEstudiante, $idInstructor, $idHorario)
    {
        // Consultamos la información del usuario logueado
        $instructor = $this->datosInstructor($idInstructor);
        $estudiante = $this->datosEstudiante($idEstudiante);
        $eventoAgendaEntrenador = $this->eventoAgendaEntrenador($idHorario);
        $linkClaseReservada = $this->linkClaseReservada($idHorario);

        if(
            (isset($instructor) && !empty($instructor) && !is_null($instructor)) &&
            (isset($estudiante) && !empty($estudiante) && !is_null($estudiante)) &&
            (isset($eventoAgendaEntrenador) && !empty($eventoAgendaEntrenador) && !is_null($eventoAgendaEntrenador)) &&
            (isset($linkClaseReservada) && !empty($linkClaseReservada) && !is_null($linkClaseReservada))
        )
        {
            //Envio del correo
            Mail::to($instructor->correo)->send(new MailReservaClase($instructor,$estudiante,$eventoAgendaEntrenador,$linkClaseReservada));
        }
    }

    // ==============================================================
    // ==============================================================
    
    public function datosInstructor($idInstructor)
    {
        try
        {
            return User::find($idInstructor);

        } catch (Exception $e)
        {
            Logger("Error consultando los datos del usuario administrador: {$e}");
            return "error_datos_admin";
        }
    }

    // ==============================================================
    // ==============================================================

    public function datosEstudiante($idEstudiante)
    {
        try
        {
            return User::find($idEstudiante);

        } catch (Exception $e)
        {
            Logger("Error consultando los datos del usuario: {$e}");
            return "error_datos_estudiante";
        }
    }

    // ==============================================================
    // ==============================================================

    public function eventoAgendaEntrenador($idHorario)
    {
        try
        {
            return EventoAgendaEntrenador::find($idHorario);
        } catch (Exception $e)
        {
            dd($e);
            Logger("Error consultando los datos del usuario administrador: {$e}");
            return "error_datos_disponibilidad";
        }
    }
    
    // ==============================================================
    // ==============================================================

    public function linkClaseReservada($idHorario)
    {
        try
        {
            return Reserva::select('link_meet')
                ->where('id_trainer_horario', $idHorario)->first();
        } catch (Exception $e)
        {
            dd($e);
            Logger("Error consultando los datos del usuario administrador: {$e}");
            return "error_datos_disponibilidad";
        }
    }
} // FIN class ReservarClase
