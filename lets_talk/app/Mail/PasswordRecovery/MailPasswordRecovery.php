<?php

namespace App\Mail\PasswordRecovery;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailPasswordRecovery extends Mailable //implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $idUserRecovery;
    public $usuarioRecovery;
    public $correoRecovery;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($idUserRecovery, $usuarioRecovery, $correoRecovery)
    {
        $this->idUserRecovery = $idUserRecovery;
        $this->usuarioRecovery = $usuarioRecovery;
        $this->correoRecovery = $correoRecovery;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.PasswordRecovery.passwordRecoveryMail')
                    ->subject('Password Recovery Info');
    }
}
