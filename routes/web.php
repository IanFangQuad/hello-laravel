<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserController;

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

Route::match(['get'], '/', [IndexController::class, 'Index'])->name('Index');
Route::match(['post'], '/LogIn', [IndexController::class, 'LogIn']);
Route::match(['post'], '/LogOut', [IndexController::class, 'LogOut']);
Route::match(['get'], '/user/{userID}', [UserController::class, 'Get'])->middleware('auth:login');
