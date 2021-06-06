<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EscalafonController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SchoolController;
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
    Route::post('/faculties/{id}/schools', [SchoolController::class, 'store']);
    Route::get('/faculties/{id}/schools', [SchoolController::class, 'all']);
    Route::get('/schools/{id}', [SchoolController::class, 'show']);
    Route::put('/schools/{id}', [SchoolController::class, 'update']);
    Route::delete('/schools/{id}', [SchoolController::class, 'destroy']);

    //Rutas que maneja el catalogo de escuelas pertenecientes a facultades

    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
