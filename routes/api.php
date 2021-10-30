<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\StudentController;

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

Route::get('/v1/animals', [AnimalController::class, 'index']);
Route::post('/v1/animals', [AnimalController::class, 'store']);
Route::put('/v1/animals/{id}', [AnimalController::class, 'update']);
Route::delete('/v1/animals/{id}', [AnimalController::class, 'destroy']);

Route::get('/v1/students', [StudentController::class, 'index']);
Route::post('/v1/students', [StudentController::class, 'store']);
Route::put('/v1/students/{id}', [StudentController::class, 'update']);
Route::delete('/v1/students/{id}', [StudentController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
