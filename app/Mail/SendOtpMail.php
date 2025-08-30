<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public string $otp,
        public ?Company $company = null,
        public string $purpose = 'pendaftaran'
    ){
        
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
       
        $subject = 'Kode Verifikasi';

        if ($this->purpose === 'reset') {
            $subject = 'Kode OTP Reset Password';
        } else {
            $subject = 'Kode Verifikasi Pendaftaran Desa';
            if ($this->company) {
                $companyName = str_replace('Desa ', '', $this->company->name);
                $subject = 'Kode Verifikasi Pendaftaran Akun Warga ' . $companyName;
            }
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.send-otp',
        );
    }
}
