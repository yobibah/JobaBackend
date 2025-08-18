<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // accessible dans la vue

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Bienvenue sur JobaBackend !")
                    ->view('emails.inscription'); // Vue HTML/Blade
    }
}
