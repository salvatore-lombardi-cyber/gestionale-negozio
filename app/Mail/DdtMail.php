<?php

namespace App\Mail;

use App\Models\Ddt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class DdtMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $ddt;
    
    public function __construct(Ddt $ddt)
    {
        $this->ddt = $ddt;
    }
    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'DDT ' . $this->ddt->numero_ddt . ' - ' . $this->ddt->cliente->nome_completo,
        );
    }
    
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ddt',
        );
    }
    
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdfs.ddt', ['ddt' => $this->ddt]);
        
        return [
            Attachment::fromData(fn() => $pdf->output(), 'DDT-' . $this->ddt->numero_ddt . '.pdf')
            ->withMime('application/pdf'),
        ];
    }
}