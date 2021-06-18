<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EscalafonController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContractTypeController;
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
    Route::post('/faculties', [FacultyController::class, 'store']);
    Route::get('/faculties', [FacultyController::class, 'all']);
    Route::get('/faculties/{id}', [FacultyController::class, 'show']);
    Route::put('/faculties/{id}', [FacultyController::class, 'update']);
    Route::delete('/faculties/{id}', [FacultyController::class, 'destroy']);

    //Rutas que maneja el catalogo de escuelas pertenecientes a facultades
    Route::post('/faculties/{id}/schools', [SchoolController::class, 'store']);
    Route::get('/faculties/{id}/schools', [SchoolController::class, 'all']);
    Route::get('/schools/{id}', [SchoolController::class, 'show']);
    Route::put('/schools/{id}', [SchoolController::class, 'update']);
    Route::delete('/schools/{id}', [SchoolController::class, 'destroy']);

    //Rutas que maneja el catalogo de materias pertenecientes a escuelas
    Route::post('/schools/{id}/courses', [CourseController::class, 'store']);
    Route::get('/schools/{id}/courses', [CourseController::class, 'all']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

    //Rutas que maneja el catalogo de escalafones
    Route::post('/contract-types', [ContractTypeController::class, 'store']);
    Route::get('/contract-types', [ContractTypeController::class, 'all']);
    Route::get('/contract-types/{id}', [ContractTypeController::class, 'show']);
    Route::put('/contract-types/{id}', [ContractTypeController::class, 'update']);
    Route::delete('/contract-types/{id}', [ContractTypeController::class, 'destroy']);


});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
