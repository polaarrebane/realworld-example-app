<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::namespace('Api')
    ->name('api.')
    ->group(function () {

        // USERS
        Route::namespace('Users')->name('users.')->prefix('users')
            ->withoutMiddleware('protected')->group(function () {
                Route::post('', [UserController::class, 'store'])->name('create');
            });
    });
