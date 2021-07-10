<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CentralAuthorityController;
use App\Http\Controllers\EscalafonController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\PersonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

    // Ruta que maneja la bitacora de uso 
    Route::group(['middleware' => ['can:read_worklog']], function () {
        Route::get('/worklog', [AuthController::class, 'worklog']);
    });

    //Rutas que maneja el catalogo de escalafones 
    Route::group(['middleware' => ['can:write_escalafones']], function () {
        Route::post('/escalafones', [EscalafonController::class, 'store']);
        Route::put('/escalafones/{id}', [EscalafonController::class, 'update']);
        Route::delete('/escalafones/{id}', [EscalafonController::class, 'destroy']);
    });
    Route::group(['middleware' => ['can:read_escalafones']], function () {
        Route::get('/escalafones', [EscalafonController::class, 'all']);
        Route::get('/escalafones/{id}', [EscalafonController::class, 'show']);
    });

    // Rutas que maneja el catalogo de facultades
    Route::group(['middleware' => ['can:write_faculties']], function () {
        Route::post('/faculties', [FacultyController::class, 'store']);
        Route::put('/faculties/{id}', [FacultyController::class, 'update']);
        Route::delete('/faculties/{id}', [FacultyController::class, 'destroy']);
    });
    Route::group(['middleware' => ['can:read_faculties']], function () {
        Route::get('/faculties', [FacultyController::class, 'all']);
        Route::get('/faculties/{id}', [FacultyController::class, 'show']);
    });

    // Rutas que maneja el catalogo de escuelas pertenecientes a facultades
    Route::group(['middleware' => ['can:write_schools']], function () {
        Route::post('/faculties/{id}/schools', [SchoolController::class, 'store']);
        Route::put('/schools/{id}', [SchoolController::class, 'update']);
        Route::delete('/schools/{id}', [SchoolController::class, 'destroy']);
    });
    Route::group(['middleware' => ['can:read_schools']], function () {
        Route::get('/faculties/{id}/schools', [SchoolController::class, 'all']);
        Route::get('/schools/{id}', [SchoolController::class, 'show']);
    });

    // Rutas que manejan el catalogo de actividades de docentes
    Route::group(['middleware' => ['can:write_activities']], function () {
        Route::post('/activities', [ActivityController::class, 'store']);
        Route::put('/activities/{id}', [ActivityController::class, 'update']);
        Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);
    });
    Route::group(['middleware' => ['can:read_activities']], function () {
        Route::get('/activities', [ActivityController::class, 'all']);
        Route::get('/activities/recommended', [ActivityController::class, 'recommended']);
        Route::get('/activities/{id}', [ActivityController::class, 'show']);
    });

    // Rutas que maneja el catalogo de materias pertenecientes a escuelas
    Route::group(['middleware' => ['can:write_courses']], function () {
        Route::post('/schools/{id}/courses', [CourseController::class, 'store']);
        Route::put('/courses/{id}', [CourseController::class, 'update']);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    });
    Route::group(['middleware' => ['can:read_courses']], function () {
        Route::get('/schools/{id}/courses', [CourseController::class, 'all']);
        Route::get('/courses/{id}', [CourseController::class, 'show']);
    });

    // Rutas que maneja el catalogo de escalafones
    Route::group(['middleware' => ['can:write_contractTypes']], function () {
        Route::post('/contract-types', [ContractTypeController::class, 'store']);
        Route::put('/contract-types/{id}', [ContractTypeController::class, 'update']);
        Route::delete('/contract-types/{id}', [ContractTypeController::class, 'destroy']);
    });
    Route::group(['middleware' => ['can:read_contractTypes']], function () {
        Route::get('/contract-types', [ContractTypeController::class, 'all']);
        Route::get('/contract-types/{id}', [ContractTypeController::class, 'show']);
    });

    // Rutas que manejan el catalogo de autoridades centrales
    Route::group(['middleware' => ['can:write_centralAuthorities']], function () {
        Route::post('/central-authorities', [CentralAuthorityController::class, 'store']);
        Route::put('/central-authorities/{id}', [CentralAuthorityController::class, 'update']);
        Route::delete('/central-authorities/{id}', [CentralAuthorityController::class, 'destroy']);
    });
    Route::group(['middleware' => ['can:read_centralAuthorities']], function () {
        Route::get('/central-authorities', [CentralAuthorityController::class, 'all']);
        Route::get('/central-authorities/{id}', [CentralAuthorityController::class, 'show']);
    });

    //Rutas de informacion de persona a contratar
    Route::group(['middleware' => ['can:write_persons']], function () {
        Route::post('/persons', [PersonController::class, 'store']);
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
    Route::group(['middleware' => ['can:read_persons']], function () {
        Route::get('/persons/{id}', [PersonController::class, 'show']);
    });

    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $user = Auth::user();
});
