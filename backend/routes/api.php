<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RelationshipController;

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

Route::group(["prefix" => "v0.1"], function () {

    Route::controller(UserController::class)->group(function () {
        Route::middleware(['auth:api'])->group(function () {
            Route::get('logout', 'logout');
            Route::get('refresh', 'refresh');
            Route::post('editUser', 'editUser');
        });
        Route::get('image/{filename?}', 'shareImages');
        Route::post('register', 'register');
        Route::post('login', 'login');
    });

    Route::controller(MessageController::class)->group(function () {
        Route::middleware(['auth:api'])->group(function () {
            Route::post('sendMessage', 'sendMessage');
            Route::get('receiveMessages/{id?}', 'receiveMessages');
        });
    });

    Route::controller(RelationshipController::class)->group(function () {
        Route::middleware(['auth:api'])->group(function () {
            Route::get('getUsers', 'getUsers');
            Route::get('getFavorites', 'getFavorites');
            Route::get('getBlocked', 'getBlocked');
            Route::get('toggleFavorites/{id?}', 'toggleFavorites');
            Route::get('toggleBlock/{id?}', 'toggleBlock');
        });
    });
});
