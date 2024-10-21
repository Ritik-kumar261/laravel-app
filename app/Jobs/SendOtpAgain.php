<?php

namespace App\Jobs;

use App\Mail\OtpAgain;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendOtpAgain implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $email;
    protected $otp;
   

     public function __construct($email,$otp)
    {
        $this->email = $email;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new OtpAgain($this->otp));
    }
}
