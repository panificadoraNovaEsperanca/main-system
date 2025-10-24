<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InsumoAbaixoDoMinimoMail extends Mailable
{
    public $insumo;

    public function __construct($insumo)
    {
        $this->insumo = $insumo;
    }

    public function build()
    {
        return $this->subject('Insumo abaixo do mínimo')
            ->view('emails.insumo_abaixo_minimo');
    }
}

