<?php

namespace App\Mail;

use App\Models\VerifikasiPenduduk;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifikasiStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verifikasi;

    public function __construct(VerifikasiPenduduk $verifikasi)
    {
        $this->verifikasi = $verifikasi;
    }

    public function envelope(): Envelope
    {
        $subject = $this->verifikasi->status === 'approved'
            ? 'Status Verifikasi Data Kependudukan Anda Disetujui'
            : 'Status Verifikasi Data Kependudukan Anda Ditolak';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        // Selalu arahkan ke satu file view dan kirim seluruh objek verifikasi
        return new Content(
            markdown: 'emails.verifikasi.status',
            with: [
                'verifikasi' => $this->verifikasi,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
