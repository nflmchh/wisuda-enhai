<?php

namespace App\Mail;

use App\Models\Participant;
use App\Services\QrCodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $qrSvg;

    public function __construct(public Participant $participant)
    {
        $qrService = new QrCodeService();
        $this->qrSvg = $qrService->getQrCodeSvg($participant);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan Wisuda - QR Code Kehadiran - ' . $this->participant->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation',
        );
    }
}
