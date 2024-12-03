<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
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

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/boards', [BoardController::class, 'index'])->name('boards.index');
Route::get('/boards/{id}', [BoardController::class, 'show'])->name('boards.show');
Route::post('/boards', [BoardController::class, 'store'])->name('boards.store'); 
Route::post('/boards/{id}', [BoardController::class, 'desroy'])->name('boards.desroy');

