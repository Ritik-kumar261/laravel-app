<?php

namespace App\Http\Controllers;

use App\Jobs\SendOtpAgain;
use Illuminate\Http\Request;
use Log;

class SendotpController extends Controller
{
  public function sendOtp(Request $request)
  {
    Log::info("" . $request->email);

    $request->validate([
      "email" => "required|email",
    ]);

    $otp = rand(100000, 999999);
    session(["otp" => (string) $otp]);
    SendOtpAgain::dispatch($request->email, $otp);
    return response()->json(['success', 'otp send succes full']);
  }
  public function token(Request $request)
  {
    echo csrf_token();
  }
  public function otpVerrify(Request $request)
  {
    $request->validate([
      'otp' => 'required|digits:6|numeric',
    ]);
    Log::info('' . $request->otp);

    $sessionOtp = session('otp');
    Log::info('' . $sessionOtp);

    if ((string) $sessionOtp !== $request->otp) {
      return response()->json(['message' => 'Invalid or expired OTP.'], 401);
    }
    session()->forget(['otp']);

    // Return success response
    return response()->json(['message' => 'OTP verified successfully.'], 200);
  }
}
