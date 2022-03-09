<?php

use App\Http\Controllers\ChargeController;
use App\Http\Controllers\RecurringExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('recurring_expenses', RecurringExpenseController::class);
Route::apiResource('recurring_expenses.charges', ChargeController::class);
