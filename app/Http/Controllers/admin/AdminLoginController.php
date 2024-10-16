<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Validator;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view("login-signup.admin.login");
    }
    //admin login method
    public function authenticatUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        //    dd($validator);

        if ($validator->passes()) {
            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
             if (Auth::guard('admin')->user()->role !='admin') {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with('error', 'Either email or password is incorrect');
             }

                return redirect()->route('admin.dashboard');

            } else {
                return redirect()->route('admin.login')->with('error', 'Either email or password is incorrect');

            }
        } else {
            //validation error
            return redirect()->route('admin.login')
                ->withInput()
                ->withErrors($validator);
        }

    }
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success','logout');
    }
}
