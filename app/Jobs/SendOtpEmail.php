<?php

// App/Jobs/SendOtpEmail.php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use App\Mail\OtpMail;

class SendOtpEmail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $email;
    protected $otp;

    public function __construct($email, $otp) {
        $this->email = $email;
        $this->otp = $otp;
        
    }

    public function handle() {
        Mail::to($this->email)->send(new OtpMail($this->otp));
        
    }
}