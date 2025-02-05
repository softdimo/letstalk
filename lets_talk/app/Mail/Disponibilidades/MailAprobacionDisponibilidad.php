<?php

namespace App\Mail\Disponibilidades;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailAprobacionDisponibilidad extends Mailable //implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $info_usuario;
    public $info_admin;
    public $disponibilidades;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datos_usuario, $datos_admin, $disponibilidad)
    {
        $this->info_usuario = $datos_usuario;
        $this->info_admin = $datos_admin;
        $this->disponibilidades = $disponibilidad;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.disponibilidades.solicitud_disponibilidad')->subject('Aprobación Diponibilidad Entrenador ' . $this->info_usuario->nombres . " " . $this->info_usuario->apellidos);
    }
}
