<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CustomUser;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    //
    public function index(){
        $students = CustomUser::where("role","student")->get();
        return view("login-signup.admin.adminpage",compact("students"));
    }
    public function destroy($id)
    {
        $user = CustomUser::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }
    

}
