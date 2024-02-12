<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::namespace('Api')
    ->name('api.')
    ->group(function () {

        // USERS
        Route::namespace('Users')->name('users.')->prefix('users')
            ->withoutMiddleware('protected')->group(function () {
                Route::post('', [UserController::class, 'store'])->name('create');
                Route::post('login', [UserController::class, 'login'])->name('login');
            });

        // USER
        Route::namespace('User')->name('user.')->prefix('user')
            ->middleware('protected')->group(function () {
                Route::get('', [UserController::class, 'get'])->name('get');
                Route::put('', [UserController::class, 'update'])->name('update');
            });

        // ARTICLES
        Route::namespace('Articles')->name('articles.')->prefix('articles')
            ->middleware('protected')->group(function () {
                Route::post('', [ArticleController::class, 'store'])->name('create');
                Route::delete('{article:slug}', [ArticleController::class, 'destroy'])->name('delete');

                Route::post('{article:slug}/favorite', [ArticleController::class, 'favorite'])->name('favorite');
            });
    });
