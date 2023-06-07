<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthenticationController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/redirect/{driver}', [AuthController::class, 'redirect'])
        ->name('auth.redirect');
    Route::get('auth/callback/{driver}', [AuthController::class, 'callback'])
        ->name('auth.callback');

    Route::get("auth/google/login", [GoogleAuthenticationController::class, "loginPage"])->name("auth.google.login");
    Route::get("auth/google/redirect", [GoogleAuthenticationController::class, "redirectPage"])->name("auth.google.redirect");
});
