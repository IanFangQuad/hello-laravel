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

Route::get('/', [IndexController::class, 'index'])->middleware('auth')->name('index');

Route::get('/signup', [IndexController::class, 'signup']);
Route::post('/register', [IndexController::class, 'register']);

Route::get('/login_page', [IndexController::class, 'loginPage'])->name('login');
Route::post('/login', [IndexController::class, 'login']);
Route::post('/logout', [IndexController::class, 'logout']);

Route::get('/user/{id}', [UserController::class, 'get'])->middleware('auth');
