<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

/**
 * TODO: improve feed proximity by locale
 * TODO: add message system
*/

Route::prefix('auth')->group(function(){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::prefix('user')->middleware(['auth:sanctum'])->group(function(){
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/feed', [UserController::class, 'feed']);
    Route::put('/update', [UserController::class, 'update']);
    Route::post('/like', [UserController::class, 'like']);
    Route::get('/matches', [UserController::class, 'matches']);
    Route::delete('/unmatch', [UserController::class, 'unmatch']);
    Route::get('/get', [UserController::class, 'get']);
});
