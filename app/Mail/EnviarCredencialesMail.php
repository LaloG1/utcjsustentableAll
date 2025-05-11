<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class EnviarCredencialesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $matricula;
    public $password;

    public function __construct($matricula, $password)
    {
        $this->matricula = $matricula;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('UTCJ-SUSTENTABLE: Tus credenciales de acceso')
                    ->view('emails.credenciales'); // Vista del correo
    }
}