<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\QaController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/user/signup', [AuthController::class, 'signupStore'])->name('signupStore');
Route::post('/user/signin', [AuthController::class, 'login'])->name('login');
Route::post('/submit/project', [QaController::class, 'submitProject']);
Route::get('/user/{user_id}/fetch/projects', [QaController::class, 'fetchUserProjects']);
Route::get('/fetch/all/projects', [QaController::class, 'fetchAllProjects']);
Route::get('/fetch/project/{id}/details', [QaController::class, 'fetchProjectDetails']);