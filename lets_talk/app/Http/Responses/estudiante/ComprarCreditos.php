<?php

namespace App\Http\Responses\estudiante;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\estudiante\Credito;
use GuzzleHttp\Client;
use App\Models\estudiante\Paquete;
use App\Http\Responses\administrador\UsuariosStore;
use App\User;
use Carbon\Carbon;

class ComprarCreditos implements Responsable
{
    public function toResponse($request)
    {
        try
        {
            $idEstudiante = session('usuario_id');
            $idPaquete = intval(request('cantidad_creditos', null));
            $paqueteActual = Credito::where('id_estudiante', $idEstudiante)->max('paquete') ?? 0;
            $infoPaquete = $this->getInfoPaquete($idPaquete);
            $datosUsuario = $this->getDatosUsuario($idEstudiante);
            $messageError = "";
            $datosOrdenCompra = array();

            if(is_null($idPaquete) || empty($idPaquete) ||
                $infoPaquete == "error_paquete" || $datosUsuario == "error_usuario")
            {
                $messageError .= 'Ha ocurrido un error inesperado, íntente de nuevo';
            } else
            {
                $body = $this->construirArrayDatos($infoPaquete, $datosUsuario, $datosOrdenCompra);

                $client = new Client([
                    'base_uri' => env('URL_API_PAYVALIDA'),
                    'headers' => [
                        'Content-Type' => 'application/json; charset=utf-8'
                    ],
                    'body' => json_encode($body)
                ]);

                $response = $client->request('POST');
                $resultado = json_decode($response->getBody()->getContents(), true);
                
                if($resultado['CODE'] != "0000" ||
                    $resultado['DESC'] != "OK" || is_null($resultado['DATA']))
                {
                    alert()->error('Error', $resultado['DESC']);
                    return redirect()->to(route('estudiante.mis_creditos'));
                } else
                {
                    DB::connection('mysql')->beginTransaction();
                
                    for ($i=1; $i <= $infoPaquete->cantidad ; $i++)
                    {
                        $paquete = $paqueteActual + 1; // Calcular el valor del paquete para esta iteración

                        $compraCredito = Credito::create([
                            'id_estado' => 7,
                            'id_estudiante' => $idEstudiante,
                            'paquete' => $paquete,
                            'fecha_credito' => time(),
                            'url_checkout_payvalida' => $resultado['DATA']['checkout'],
                            'id_paquete' => $idPaquete
                        ]);
                    }

                    if($compraCredito)
                    {
                        DB::connection('mysql')->commit();
                        return redirect('https://'.$resultado['DATA']['checkout']);

                    } else
                    {
                        DB::connection('mysql')->rollback();
                        $messageError .= "Ha ocurrido un error, íntente de nuevo, si el problema persiste, contacte a soporte.!";
                    }
                }
            }

        } catch (Exception $e)
        {
            DB::connection('mysql')->rollback();
            $messageError .= 'Ha ocurrido un error, íntente de nuevo, si el problema persiste, contacte a soporte.!';
        }

        if(empty($messageError) || $messageError != "")
        {
            alert()->error('Error', $messageError);
            return redirect()->to(route('estudiante.mis_creditos'));
        }
    }

    private function construirArrayDatos($paquete, $usuario, $datosOrdenCompra)
    {
        $data = array();
        $usuarioStore = new UsuariosStore();

        $email = $usuario->correo;
        $pais = intval(env('COUNTRY'));
        $orden = str_replace(" ", "", $usuarioStore->quitarCaracteresEspeciales($paquete->nombre_paquete)) .
                    Carbon::now()->format('dmY') . $usuario->id_user;
        $moneda = env('MONEY');
        $monto = $paquete->valor_paquete;
        $cheksum = $email . $pais . $orden . $moneda . $monto . env('FIXED_HASH');
        $hashed = hash('sha512', $cheksum);

        $data = [
            "merchant" => env('MERCHANT'),
            "email" => $email,
            "country" => $pais,
            "order" => $usuarioStore->quitarCaracteresEspeciales($orden),
            "reference" => "",
            "money" => $moneda,
            "amount" => strval($monto),
            "description" => $usuarioStore->quitarCaracteresEspeciales($orden),
            "method" => "",
            "language" => "es",
            "recurrent" => false,
            "expiration" => Carbon::now()->addDays('5')->format('d/m/Y'),
            "iva" => "0",
            "checksum" => $hashed,
            "user_di" => $usuario->numero_documento,
            "user_type_di" => $usuario->documento->abreviatura,
            "user_name" => $usuario->nombres,
            "redirect_timeout" => "300000"
        ];

        array_push($datosOrdenCompra, $data);
        return $data;
    }

    private function getInfoPaquete($idPaquete)
    {
        try
        {
            return Paquete::where('id_paquete', $idPaquete)->first();

        } catch (Exception $e) {
            return "error_paquete";
        }
    }

    private function getDatosUsuario($idEstudiante)
    {
        try
        {
            return User::with('documento')->where('id_user', $idEstudiante)->first();

        } catch (Exception $e) {
            return "error_usuario";
        }
    }
}
