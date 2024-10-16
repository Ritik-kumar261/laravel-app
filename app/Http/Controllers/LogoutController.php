<?php

namespace App\Http\Controllers;

use App\Jobs\SendOtpEmail;
use App\Models\CustomUser;
use Auth;
use Hash;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Log;
use Psy\Readline\Hoa\Console;
use Validator;

class LogoutController extends Controller
{
  //
  public function index()
  {

    return view("login-signup.updatepage");
  }

  // update profile
  public function update(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . Auth::id(),
    ]);

    $user = Auth::user();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return redirect()->route('account.dashboard')->with('success', 'Profile updated successfully.');
  }

  // here we change the password of user 
  public function changePassword(Request $request)
  {
    // Validate incoming request
    $validator = Validator::make($request->all(), [
      'inputPassword1' => 'required|min:8|',
      'inputPassword2' => 'required|min:8',
    ]);
    Log::info('Input Password 1: ' . $request->input('inputPassword1'));
    Log::info('Input Password 2: ' . $request->input('inputPassword2'));
    // Check if validation fails
    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = Auth::user();

    // // Check if the current password matches the existing password
    // if (!Hash::check($request->inputPassword2, $user->password)) {
    //     return response()->json(['message' => 'Current password is incorrect.'], 401);
    // }

    // Check if the new password is the same as the current password
    if (Hash::check($request->inputPassword1, $user->password)) {
      return response()->json(['message' => 'This password already exists, please choose a new one.'], 409);
    }

    // Update the password
    $user->password = Hash::make($request->inputPassword1);
    $user->save();

    return response()->json(['message' => 'Password changed successfully.'], 200);
  }


  // here for otp 
  public function sendOtp(Request $request)
  {
    Log::info(''. $request->input('email'));
    $request->validate([
      'email' => 'required|email',
    ]);
    // Get the currently authenticated user
    $user = Auth::user();

    // Check if the email matches the logged-in user's email
    if ($user->email !== $request->email) {
      return response()->json(['message' => 'Invalid email, please provide the email associated with your account.'], 400);
    }
    $otp = rand(100000, 999999); // Generate a 6-digit OTP
    session(['otp' => (string) $otp]);  // Store OTP in session
    session(['otp_expiry' => now()->addMinutes(5)]); // Store expiry time in session

    // Dispatch the job to send the OTP
    SendOtpEmail::dispatch($user->email, $otp);
    //dd(session('otp'), ['email'=> $user->email]);
    return response()->json(['message' => 'OTP sent successfully.'], 200);

  }
  // now here check the otp 
  public function verifyOtp(Request $request)
  {
     Log::info(''. $request->input('otp'));

    $request->validate([
      'otp' => 'required|digits:6|numeric',
    ]);

    // Retrieve the OTP and expiry time from the session
    $sessionOtp = session('otp');
    $otpExpiry = session('otp_expiry');

    // Check if the OTP matches and is not expired
    if ((string) $sessionOtp !== $request->otp || now()->isAfter($otpExpiry)) {
      return response()->json(['message' => 'Invalid or expired OTP.'], 401);
    }

    // Clear the OTP from the session
    session()->forget(['otp', 'otp_expiry']);

    // Return success response
    return response()->json(['message' => 'OTP verified successfully.'], 200);
  }

}