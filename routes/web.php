<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveController;
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
		Route::post('logout', 'logout');
        Route::get('/', 'index')->name('index');
	});

	Route::controller(UserController::class)->group(function () {
		Route::get('user/{id}', 'show');
	});

	Route::prefix('leave')->controller(LeaveController::class)->group(function () {
		Route::post('/', 'create');
		Route::delete('{id}', 'delete');
		Route::patch('{id}', 'update');
	});

});

Route::controller(IndexController::class)->group(function () {
	Route::get('login_page', 'loginPage')->name('login');
	Route::post('login', 'login');

	Route::get('signup', 'signup');
	Route::post('register', 'register');

});
