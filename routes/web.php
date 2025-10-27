<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\ActivityLogsController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PermissionsController;
use App\Http\Controllers\Backend\ProfilInstansiController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\SiteInfoController;
use App\Http\Controllers\Backend\UserProfileController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Route;

// Auth Login
Route::controller(AuthController::class)->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/', 'index')->name('login')->middleware('guest');
        Route::get('/logout', 'logout_sessions')->name('logout_sessions');
    });
});
// Backend
Route::group(['prefix' => ''], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        // Dasboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        //Kelola Aplikasi
        Route::get('/manage_siteinfo', [SiteInfoController::class, 'index'])->name('manage_siteinfo');
        Route::get('/manage_profilinstansi', [ProfilInstansiController::class, 'index'])->name('manage_profilinstansi');
        //Kelola Pengguna
        Route::get('/manage_permissions', [PermissionsController::class, 'index'])->name('manage_permissions');
        Route::get('/manage_roles', [RolesController::class, 'index'])->name('manage_roles');
        Route::get('/manage_users', [UsersController::class, 'index'])->name('manage_users');
        Route::get('/user_activities', [ActivityLogsController::class, 'index'])->name('user_activities');
        /* Route::get('/mailresetpassword', function () {
            return view('mail_templated.password_reset');
        }); */
        // Auth Logout
        Route::group(['prefix' => 'auth'], function () {
            Route::get('/logout', [AuthController::class, 'logout_sessions'])->name('logout_authsessions');
        });
        //Profil User
        Route::get('/{username}', [UserProfileController::class,'index'])->name('user_profile');
    });
});
