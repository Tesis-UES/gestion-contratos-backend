<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EscalafonController;
use App\Http\Controllers\FacultyController;
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

    //Rutas que maneja el catalogo de escalafones
    Route::post('/escalafones', [EscalafonController::class, 'store']);
    Route::get('/escalafones', [EscalafonController::class, 'all']);
    Route::get('/escalafones/{id}', [EscalafonController::class, 'show']);
    Route::put('/escalafones/{id}', [EscalafonController::class, 'update']);
    Route::delete('/escalafones/{id}', [EscalafonController::class, 'destroy']);

    //Rutas que maneja el catalogo de facultades
    Route::post('/faculty', [FacultyController::class, 'store']);
    Route::get('/faculty', [FacultyController::class, 'all']);
    Route::get('/faculty/{id}', [FacultyController::class, 'show']);
    Route::put('/faculty/{id}', [FacultyController::class, 'update']);
    Route::delete('/faculty/{id}', [FacultyController::class, 'destroy']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
