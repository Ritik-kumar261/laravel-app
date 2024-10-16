<?php
namespace App\Http\Middleware\CheckLoginMiddleware;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\BulkuploadController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DashBoard;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\ListDataController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RewardPointController;
use App\Http\Controllers\NumberOfDaysController;

Route::get('/', function () {
    return view('welcome');
});
//Route::get('/reward-points', [RewardPointController::class, 'index'])->name('reward.point');
// Route::get('/reward-points/{year}/{month}', [RewardPointController::class, 'index'])->name('reward.point');
// Route::get('/level-data', [NumberOfDaysController::class, 'index'])->name('level-data');
//Route::get('/level-data/{level?}/{power?}/{core?}/{casual?}',[NumberOfDaysController::class,'index'])->name('level.data');
Route::post('/level-data/export-excel', [NumberOfDaysController::class, 'exportExcel'])->name('export-excel');
Route::get('/download-data', [ChartController::class, 'downloadData'])->name('download.data');

Route::post('/dashboard/import-data', [BulkuploadController::class, 'index'])->name('import-data.csv');
// here we deifne the route of login and signup

//logout



// here we define the middleware for checking the user login credential
// Route::middleware('check_login')->get('/account/dashboard', [DashBoard::class, 'index'])->name('account.dashboard');/
// here we done of groupuing 
Route::group(['prefix' => 'account'], function () {



    Route::group(['middleware' => 'guest'], function () {
        //guest midleware
        // only when unautherised user
        Route::get('/login', [LoginController::class, 'login'])->name('account.login');
        Route::get('/register', [LoginController::class, 'register'])->name('account.register');
        Route::post('/process-register', [LoginController::class, 'processRegister'])->name('account.processregister');
        Route::post('/authenticate', [LoginController::class, 'authenticatUser'])->name('account.authenticate');

    });
    //authenmticate middleware
    Route::group(['middleware' => 'auth'], function () {
        //login route only logged user 
        Route::get('/logout', [LoginController::class, 'logout'])->name('account.logout');
        Route::get('/home', [HomePageController::class, 'index'])->name('account.home');
        //list route
        Route::get('/dashboard', [DashBoard::class, 'index'])->name('account.dashboard');
        Route::post('/dashboard/status/{id}',[DashBoard::class,'statusChange'])->name('status.change');
        //chart route
        Route::get('/chartdashboard', [ChartController::class, 'index'])->name('account.chartdashboard');
        Route::get('/chart/dashboard-data', [ChartController::class, 'chartData'])->name('account.chartdata');

        Route::get('/level-data', [NumberOfDaysController::class, 'index'])->name('level-data');
        // update route 
        Route::post('/account/update', [LogoutController::class, 'update'])->name('account.update');
        Route::post('/account/change-password', [LogoutController::class, 'changePassword'])->name('account.change-password');
        Route::post('/account/email-otp', [LogoutController::class,'sendOtp'])->name('account.email-otp');
        Route::post('/account/email-otp-verify', [LogoutController::class, 'verifyOtp'])->name('account.email-otp-verify');
        //Route::get('/account/', [LogoutController::class,'index'])->name('account.updatepage');
        Route::get('account/updatepage', [LogoutController::class, 'index'])->name('account.updatepage');
        // list add and update
        Route::post('/sore/listdata', [ListDataController::class,'addList'] )->name('store.listdata');
        Route::get('/user/addlist', [ListDataController::class,'addPage'] )->name('user.addlist');
        Route::get('/user/listdata/{id?}', [ListDataController::class,'index'])->name('user.listdata');
        Route::put('/user/listdata/{id}', [ListDataController::class,'update'])->name('update.list');
        Route::get('/getdata/{level?}/{power?}/{core?}/{casual?}', [NumberOfDaysController::class,'index'])->name('getdata');
        Route::get('/reward-points/{year?}/{month?}', [RewardPointController::class, 'index'])->name('reward.point');

    });

});

// here using middleware 
// Route::group(['prefix'=> 'admin'], function () {


//     Route::group(['middleware'=> 'admin.guest'], function () {
//     Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
//     Route::post('admin/authenticate', [AdminLoginController::class, 'authenticatUser'])->name('admin.authenticate');
// });
//  Route::group(['middleware'=> 'admin.auth'], function () {

//     Route::get ('dashboard', [AdminDashboardController::class,'index'])->name('admin.dashboard');

//     Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


// });

// });
Route::delete('/admin/students/{id}', [AdminDashboardController::class, 'destroy'])->name('admin.students.destroy'); // Delete student

// here we define the route about listdata




