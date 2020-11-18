<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageRecover extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Recuperacion de Correo';
    public $mensaje;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mensaje)
    {
        //
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.messagerecover');
    }
}
