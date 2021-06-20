<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EscalafonController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\PersonController;
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

    // Rutas que manejan el catalogo de actividades de docentes
    Route::post('/activities', [ActivityController::class, 'store']);
    Route::get('/activities', [ActivityController::class, 'all']);
    Route::get('/activities/recommended', [ActivityController::class, 'recommended']);
    Route::get('/activities/{id}', [ActivityController::class, 'show']);
    Route::put('/activities/{id}', [ActivityController::class, 'update']);
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);
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

    //Rutas de informacion de persona a contratar
    Route::post('/persons', [PersonController::class, 'store']);
    Route::get('/persons/{id}', [PersonController::class, 'show']);

    Route::post('/persons/{id}/files/dui', [PersonController::class, 'storeDui']);
    Route::post('/persons/{id}/files/nit', [PersonController::class, 'storeNit']);
    Route::post('/persons/{id}/files/bank-account', [PersonController::class, 'storeBank']);
    Route::post('/persons/{id}/files/title', [PersonController::class, 'storeTitle']);
    Route::post('/persons/{id}/files/curriculum', [PersonController::class, 'storeCurriculum']);
    Route::put('/persons/{id}/files/dui', [PersonController::class, 'updateDui']);
    Route::put('/persons/{id}/files/nit', [PersonController::class, 'updateNit']);
    Route::put('/persons/{id}/files/bank-account', [PersonController::class, 'updateBank']);
    Route::put('/persons/{id}/files/title', [PersonController::class, 'updateTitle']);
    Route::put('/persons/{id}/files/curriculum', [PersonController::class, 'updateCurriculum']);

    Route::put('/persons/{id}', [PersonController::class, 'update']);
    Route::delete('/persons/{id}', [PersonController::class, 'destroy']);

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
