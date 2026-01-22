<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class OtpMail extends Mailable
{
    public string $otp;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Email Verification OTP')
            ->view('emails.otp')
            ->with([
                'otp' => $this->otp
            ]);
    }
}

