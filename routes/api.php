<?php

use App\Http\Controllers\Api\Users\EventController;
use App\Http\Controllers\Api\Users\MessageController;
use App\Http\Controllers\Api\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('user', 'user')->name('user');
    Route::get('users', 'all')->name('users');
});

Route::name('user.')->prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::name('message.')->prefix('message')->controller(MessageController::class)->group(function () {
        Route::post('/', 'store')->name('store');
    });

    Route::name('events.')->prefix('events')->controller(EventController::class)->group(function () {
        Route::post('/missed', 'missed')->name('missed');
    });
});
