<?php

namespace App\Features\Auth\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly string $otp)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Your Uranus verification code')
            ->view('emails.auth.login-otp');
    }
}
