<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::post('/logIn', [IndexController::class, 'logIn']);
Route::post('/logOut', [IndexController::class, 'logOut']);
Route::get('/user/{id}', [UserController::class, 'get'])->middleware('auth');
