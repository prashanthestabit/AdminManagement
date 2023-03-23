<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\AdminManagement\Http\Controllers\AdminManagementController;
use Modules\AdminManagement\Http\Controllers\Auth\AuthController;
use Modules\AdminManagement\Http\Controllers\Auth\ChangePasswordController;
use Modules\AdminManagement\Http\Controllers\Auth\ProfileController;
use Modules\AdminManagement\Http\Controllers\Auth\ResetPasswordController;
use Modules\AdminManagement\Http\Controllers\PermissionController;
use Modules\AdminManagement\Http\Controllers\RoleController;
use Modules\AdminManagement\Http\Controllers\UserController;

Route::prefix('adminmanagement')->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
    Route::get('registration', [AuthController::class, 'registration'])->name('register');
    Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
    Route::get('forget-password', [AuthController::class, 'showForgetPasswordForm'])->name('forget.password.get');
    Route::post('forget-password', [AuthController::class, 'submitForgetPasswordForm'])->name('forget.password.post');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'])
      ->name('reset.password.get');
    Route::post('reset-password', [ResetPasswordController::class, 'submitResetPasswordForm'])
      ->name('reset.password.post');
});

Route::group(['as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('dashboard', [AdminManagementController::class, 'index'])->name('dashboard');

    Route::get('home', [AdminManagementController::class, 'index'])->name('home');

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('users', UserController::class);

    Route::resource('roles', RoleController::class);

    Route::resource('permissions', PermissionController::class);
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    // Change password
    Route::get('show', [ProfileController::class, 'index'])->name('show');
    Route::post('update', [ProfileController::class, 'update'])->name('update');
    Route::get('password', [ChangePasswordController::class, 'index'])->name('password.edit');
    Route::post('password/update', [ChangePasswordController::class, 'updatePassword'])->name('password.update');
});
