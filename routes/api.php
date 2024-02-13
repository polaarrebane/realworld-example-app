<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
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
                Route::put('{article:slug}', [ArticleController::class, 'update'])->name('update');
                Route::delete('{article:slug}', [ArticleController::class, 'destroy'])->name('delete');

                Route::post('{article:slug}/favorite', [ArticleController::class, 'favorite'])->name('favorite');
                Route::delete('{article:slug}/favorite', [ArticleController::class, 'unfavorite'])->name('unfavorite');

                Route::get('feed', [ArticleController::class, 'feed'])->name('feed');
            })
            ->withoutMiddleware('protected')->group(function () {
                Route::get('', [ArticleController::class, 'index'])->name('get');
                Route::get('{article:slug}', [ArticleController::class, 'show'])->name('slug');
            });

        // PROFILES
        Route::namespace('Profiles')->name('profiles.')->prefix('profiles')
            ->middleware('protected')->group(function () {
                Route::post('{user:username}/follow', [ProfileController::class, 'follow'])->name('follow');
                Route::delete('{user:username}/follow', [ProfileController::class, 'unfollow'])->name('unfollow');
            })
            ->withoutMiddleware('protected')->group(function () {
                Route::get('{user:username}', [ProfileController::class, 'show'])->name('get');
            });

        // COMMENTS
        Route::namespace('Comments')->name('articles.comments.')->prefix('articles')
            ->middleware('protected')->group(function () {
                Route::post('{article:slug}/comments', [CommentController::class, 'store'])->name('create');
                Route::delete(
                    '{article:slug}/comments/{comment:id}',
                    [CommentController::class, 'destroy']
                )->name('delete');
            })
            ->withoutMiddleware('protected')->group(function () {
                Route::get('{article:slug}/comments', [CommentController::class, 'index'])->name('get');
            });
    });
