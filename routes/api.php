<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\ActivityLogsController;
use App\Http\Controllers\Backend\PermissionsController;
use App\Http\Controllers\Backend\ProfilInstansiController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\SiteInfoController;
use App\Http\Controllers\Backend\UserProfileController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Local API for internal host
Route::middleware('restrict.internal.access')->group(function () {
    Route::get('/site_info', [CommonController::class, 'site_info'])->name('site_info');
    //Auth Route
    Route::controller(AuthController::class)->group(function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('/first_login', 'first_login')->name('first_login');
            Route::post('/second_login', 'second_login')->name('second_login');
        });
    });
    //Auth Sanctum
    Route::group(['middleware' => ['auth:sanctum']], function () {
        //Common Controller
        Route::controller(CommonController::class)->group(function () {
            Route::get('/user_info', 'user_info')->name('user_info');
        });
        //Manage Site Info
        Route::post('/manage_siteinfo/update', [SiteInfoController::class, 'update'])->name('update_siteinfo');
        //Manage Profil Instansi
        Route::controller(ProfilInstansiController::class)->group(function () {
            Route::get('manage_profilinstansi/show', 'show')->name('show_profilinstansi');
            Route::post('manage_profilinstansi/update', 'update')->name('update_profilinstansi');
        });
        //Manage Permissions
        Route::controller(PermissionsController::class)->group(function () {
            Route::get('/manage_permissions/show', 'show')->name('show_permissions');
            Route::post('/manage_permissions/store', 'store')->name('store_permissions');
            Route::post('/manage_permissions/update', 'update')->name('update_permissions');
            Route::post('/manage_permissions/delete', 'delete')->name('delete_permissions');
        });
        //Manage Roles
        Route::controller(RolesController::class)->group(function () {
            Route::get('/manage_roles/show', 'show')->name('show_roles');
            Route::post('/manage_roles/store', 'store')->name('store_roles');
            Route::post('/manage_roles/update', 'update')->name('update_roles');
            Route::post('/manage_roles/delete', 'delete')->name('delete_roles');
        });
        //Manage Users
        Route::controller(UsersController::class)->group(function () {
            Route::get('/manage_users/show', 'show')->name('show_users');
            Route::post('/manage_users/store', 'store')->name('store_users');
            Route::post('/manage_users/update', 'update')->name('update_users');
            Route::post('/manage_users/delete', 'delete')->name('delete_users');
        });
        //Users Activity
        Route::controller(ActivityLogsController::class)->group(function () {
            Route::get('/user_activities/show', 'show')->name('show_logs');
            Route::post('/user_activities/delete', 'delete')->name('delete_logs');
        });
        //Profil User
        Route::controller(UserProfileController::class)->group(function () {
            Route::get('/{username}/show','show')->name('show_userprofile');
            Route::post('/{username}/update', 'update')->name('update_userprofile');
        });
    });
});
