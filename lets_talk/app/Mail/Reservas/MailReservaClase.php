<?php

namespace App\Mail\Reservas;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class MailReservaClase extends Mailable //implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $instructor;
    public $estudiante;
    public $eventoAgendaEntrenador;
    public $linkClaseReservada;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($instructor,$estudiante,$eventoAgendaEntrenador,$linkClaseReservada)
    {
        $this->instructor = $instructor;
        $this->estudiante = $estudiante;
        $this->eventoAgendaEntrenador = $eventoAgendaEntrenador;
        $this->linkClaseReservada = $linkClaseReservada;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reservas.reservar_clase')->subject('Clase Reservada por ' . $this->estudiante->nombres . " " . $this->estudiante->apellidos);
    }
}
