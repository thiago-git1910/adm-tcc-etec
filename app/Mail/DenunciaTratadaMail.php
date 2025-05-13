<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DenunciaTratadaMail extends Mailable
{
    use Queueable, SerializesModels;


    public $mailMensagem;

    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct($mensagem, $subject)
    {
        $this->mailMensagem = $mensagem;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this
        ->subject($this->subject) // Assunto do e-mail
        ->view('atendimentos.email-atendimento') // View do e-mail
        ->with([
            'mensagem' => $this->mailMensagem, // Variável passada para a view
        ]);
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'atendimentos.email-atendimento',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */


    public function attachments(): array
    {
        return [];
    }
}
