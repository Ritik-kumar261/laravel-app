<?php

namespace App\Http\Controllers;
use Illuminate\Validation\ValidationException;
use App\Models\CustomUser;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // dd(Auth::check());
        if (Auth::check()) {
            return redirect()->route('account.dashboard'); // Ensure this route does not redirect back to login

        }


        return view("login-signup.login");
    }



    public function authenticatUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        //    dd($validator);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // dd(Auth::attempt(['email' => $request ->email, 'password' =>$request->password]));
                $user = Auth::user();
                // // dd($user->role);

                if ($user->role === 'admin') {
                    // dd($user);

                    return redirect()->route('admin.login')->with('error', 'for admin access ,please login here  with admin credential');

                    // } elseif ($user->hasRole('admin')) {
                    //     dd($user);
                    // return redirect()->route('admin.page');

                } else {
                    Auth::guard('admin')->logout();
                    return redirect()->route('account.dashboard')->with('success', 'You are logged in');

                }

            } else {
                return redirect()->route('account.login')->withInput()->with('error', 'invalid credential');
            }
        } else {
            //validation error
            return redirect()->route('account.login')
                ->withInput()
                ->withErrors($validator);
        }

    }
    // here is the register logic come
    public function register()
    {

        return view('login-signup.register');
    }
    public function processRegister(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20', // Adjust max length as needed
            'email' => 'required|email|unique:users_table',
            'roll_number' => 'required|integer', // Adjust max length as needed
            'phone_number' => 'required|integer',
            'password' => 'required|confirmed',
            'url' => ['required', 'regex:/^(https?:\/\/)?(www\.)?[a-zA-Z0-9\-]+(\.[a-zA-Z]{2,})(\/.*)?.+$/']



        ]);

        if ($validator->passes()) {
            $user = new CustomUser();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->roll_number = $request->roll_number;
            $user->phone_number = $request->phone_number;
            $user->role = $request->role;
            $user->password = bcrypt($request->password);
            $user->url = $request->url;
            $user->save();


            return redirect()->route('account.login')->with('success', 'You have successfully registered');
        } else {
            // Validation error
            return redirect()->route('account.register')
                ->withInput()
                ->withErrors($validator);
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')->with('success', 'logout succesfully');
    }
}

