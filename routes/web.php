<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AuthController;
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

Route::middleware(['auth'])->group(function () {

	Route::controller(IndexController::class)->group(function () {
        Route::get('/', 'index')->name('index');
	});

    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
    });

	Route::controller(UserController::class)->group(function () {
		Route::get('user/{id}', 'show');
	});

	Route::controller(LeaveController::class)->group(function () {
		Route::post('leave', 'store');
		Route::delete('leave/{id}', 'destroy');
		Route::patch('leave/{id}', 'update');
	});

});

Route::controller(AuthController::class)->group(function () {
	Route::get('login', 'index')->name('login');
	Route::post('login', 'login');
});

Route::controller(UserController::class)->group(function () {
    Route::get('signup', 'create');
	Route::post('user', 'store');
});
