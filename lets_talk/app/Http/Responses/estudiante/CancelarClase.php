<?php

namespace App\Http\Responses\estudiante;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\usuarios\Reserva;
use App\Models\estudiante\Credito;
use App\Models\entrenador\EventoAgendaEntrenador;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\Reservas\MailCancelarClase;
use Illuminate\Log\Logger;

class CancelarClase implements Responsable
{
    public function toResponse($request)
    {
        $idEstudiante =  intval(request('id_estudiante', null));
        $idInstructor = intval(request('id_instructor', null));
        $idHorario = intval(request('id_horario', null));
        $idEstado = intval(request('id_estado', null));

        // ======================================================

        // Guardar los detalles de la cancelación en la sesión
        Session::put('detalles_cancelacion', [
            'id_estudiante' => $idEstudiante,
            'id_instructor' => $idInstructor,
            'id_horario' => $idHorario,
            'id_estado' => $idEstado,
        ]);

        // Verificar si el token de acceso está en la sesión
        if (!Session::has('google_access_token'))
        {
            return $this->redirectToGoogle();
        }

        return $this->procesoCancelacion();
    } // FIN toResponse

    // ================================================================
    // ================================================================

    public function procesoCancelacion()
    {
        $detalles = Session::get('detalles_cancelacion');
        if (!$detalles) {
            return response()->json(['status' => 'error_no_details']);
        }

        $idEstudiante = $detalles['id_estudiante'];
        $idInstructor = $detalles['id_instructor'];
        $idHorario = $detalles['id_horario'];
        $idEstado = $detalles['id_estado'];

        DB::beginTransaction();

        $idClaseReservada = Reserva::select('id_reserva', 'google_event_id')
            ->where('id_estudiante', $idEstudiante)
            ->where('id_instructor', $idInstructor)
            ->where('id_trainer_horario', $idHorario)
            ->first();

        if ($idClaseReservada) {
            try
            {
                $claseReservada = Reserva::findOrFail($idClaseReservada->id_reserva);

                $client = $this->getGoogleClient();
                $accessToken = Session::get('google_access_token');

                if (!$accessToken) {
                    throw new Exception('Access token no encontrado en la sesión.');
                }

                $client->setAccessToken($accessToken);

                if ($client->isAccessTokenExpired()) {
                    $refreshToken = $client->getRefreshToken();
                    if ($refreshToken) {
                        $client->fetchAccessTokenWithRefreshToken($refreshToken);
                        Session::put('google_access_token', $client->getAccessToken());
                    } else {
                        return $this->redirectToGoogle();
                    }
                }

                $service = new Google_Service_Calendar($client);
                $eventId = $idClaseReservada->google_event_id;
                $service->events->delete('primary', $eventId);

                $claseCancelada = $claseReservada->forceDelete();

                if ($claseCancelada) {
                    $idCreditoConsumido = Credito::select('id_credito')
                        ->where('id_estado', $idEstado)
                        ->where('id_estudiante', $idEstudiante)
                        ->where('id_instructor', $idInstructor)
                        ->where('id_trainer_agenda', $idHorario)
                        ->orderBy('id_credito', 'desc')
                        ->first();

                    if ($idCreditoConsumido) {
                        Credito::where('id_credito', $idCreditoConsumido->id_credito)
                            ->update([
                                'id_estado' => 7,
                                'id_instructor' => null,
                                'id_trainer_agenda' => null,
                                'fecha_consumo_credito' => null,
                            ]);

                        EventoAgendaEntrenador::where('id', $idHorario)
                            ->update([
                                'clase_estado' => 10,
                                'color' => '#157347',
                            ]);

                        DB::commit();
                        $this->enviarCorreoCancelarClase($idEstudiante, $idInstructor, $idHorario);
                        Session::forget('google_access_token');
                        Session::forget('detalles_cancelacion');

                        return 'clase_cancelada';
                    }
                }
            } catch (Exception $e) {
                DB::rollback();
                alert()->error('Error', 'Error Exception, contacte a Soporte');
                Session::forget('google_access_token');
                Session::forget('detalles_cancelacion');
                return redirect()->route('estudiante.index');
            }
        }

        return response()->json(['status' => 'error_no_clase']);
    }

    public function getGoogleClient()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI_CANCELAR'));
        $client->setScopes([Google_Service_Calendar::CALENDAR_EVENTS]);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        // Ignorar verificación de SSL solo para desarrollo local
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));

        // Ó Usar el archivo de certificados CA
        // $client->setHttpClient(new \GuzzleHttp\Client(['verify' => 'ruta/a/tu/cacert.pem']));

        return $client;
    }

    // ================================================================
    // ================================================================

    public function redirectToGoogle()
    {
        $client = $this->getGoogleClient();
        $authUrl = $client->createAuthUrl();
        return response()->json(['status' => 'auth_required', 'auth_url' => $authUrl]);
    }

    // ================================================================
    // ================================================================

    public function handleGoogleCallbackCancelar(Request $request)
    {
        $client = $this->getGoogleClient();

        if ($request->has('code'))
        {
            $client->authenticate($request->get('code'));
            Session::put('google_access_token', $client->getAccessToken());

            // Verificar si el token se ha almacenado correctamente
            if (Session::has('google_access_token'))
            {
                $cancelacionStatus = $this->procesoCancelacion();

                if ($cancelacionStatus === "clase_cancelada")
                {
                    alert()->success('Clase Cancelada', 'Puedes reservar nuevamente');
                    return redirect()->route('estudiante.disponibilidad');
                }
                else
                {
                    alert()->error('Error', 'Verifique el email utilizado en la reserva');
                    Session::forget('google_access_token');
                    Session::forget('detalles_cancelacion');
                    return redirect()->route('estudiante.index');
                }
            } else
            {
                alert()->success('Error', 'Falla en el almacenamiento del Access Token');
                Session::forget('google_access_token');
                Session::forget('detalles_cancelacion');
                return redirect()->route('estudiante.index');
            }
        }
    }

    // ================================================================
    // ================================================================

    public function enviarCorreoCancelarClase($idEstudiante, $idInstructor, $idHorario)
    {
        $instructor = $this->datosInstructor($idInstructor);
        $estudiante = $this->datosEstudiante($idEstudiante);
        $eventoAgendaEntrenador = $this->eventoAgendaEntrenador($idHorario);

        if(
            (isset($instructor) && !empty($instructor) && !is_null($instructor)) &&
            (isset($estudiante) && !empty($estudiante) && !is_null($estudiante)) &&
            (isset($eventoAgendaEntrenador) && !empty($eventoAgendaEntrenador) && !is_null($eventoAgendaEntrenador))
        )
        {
            //Envio del correo
            Mail::to($instructor->correo)->send(new MailCancelarClase($instructor,$estudiante,$eventoAgendaEntrenador));
        }
    }

    // ================================================================
    // ================================================================
    
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

    // ================================================================
    // ================================================================

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

    // ================================================================
    // ================================================================


    public function eventoAgendaEntrenador($idHorario)
    {
        try
        {
            return EventoAgendaEntrenador::find($idHorario);
        } catch (Exception $e)
        {
            Logger("Error consultando los datos del usuario administrador: {$e}");
            return "error_datos_disponibilidad";
        }
    }
} // FIN Class CancelarClase()
