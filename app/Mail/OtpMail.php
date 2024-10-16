<?php
// App/Mail/OtpMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Log;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
        // dd($otp);
    }

    public function build()
    {
        Log::info('Building OTP email', ['otp' => $this->otp]);
        return $this->subject('Your OTP Code')
            ->from('ritikpandey51042@gmail.com', 'Laravel App')
            ->view('emails.email')
            ->with(['otp' => $this->otp]);
    }
}
