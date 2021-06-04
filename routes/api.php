<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EscalafonController;
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

// Public Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);


// Protected Routes 
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/escalafones', [EscalafonController::class, 'store']);
    Route::get('/escalafones', [EscalafonController::class, 'all']);
    Route::get('/escalafones/{id}', [EscalafonController::class, 'show']);
    Route::put('/escalafones/{id}', [EscalafonController::class, 'update']);
    Route::delete('/escalafones/{id}', [EscalafonController::class, 'destroy']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
