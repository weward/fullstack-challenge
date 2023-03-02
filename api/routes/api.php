<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\UserController;
use \App\Models\User;

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

Route::bind('user', function($value) {
    return User::where('id', $value)->first();
});

Route::get('test', function() {
    App\Jobs\UpdateUserWeatherReport::dispatch();
});

Route::get('/', [\App\Http\Controllers\Api\UserController::class, 'index'])->name('users.index');
Route::get('/{user}', [\App\Http\Controllers\Api\UserController::class, 'view'])->name('users.view');


