<?php

use APP\Controllers\DashboardController;
use APP\Controllers\HomeController;
use APP\Controllers\LoginController;
use APP\Controllers\PostController;
use APP\Controllers\RegisterController;
use Efx\Core\Http\Middleware\AuthenticateMiddleware;
use Efx\Core\Http\Middleware\GuestMiddleware;
use Efx\Core\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),

    Route::get('/posts', [PostController::class, 'index']),
    Route::get('/posts/add', [PostController::class, 'form']),
    Route::post('/posts/add', [PostController::class, 'add']),
    Route::get('/posts/{id:\d+}', [PostController::class, 'show']),


    Route::get('/register', [RegisterController::class, 'index'], [GuestMiddleware::class]),
    Route::post('/register', [RegisterController::class, 'register'], [GuestMiddleware::class]),

    Route::get('/login', [LoginController::class, 'index'], [GuestMiddleware::class]),
    Route::post('/login', [LoginController::class, 'login'], [GuestMiddleware::class]),
    Route::post('/logout', [LoginController::class, 'logout']),


    Route::get('/dashboard', [DashboardController::class, 'index'], [AuthenticateMiddleware::class]),


    /*    Route::get('/call/{name}', function ($name) {
            return new Response("call test $name");
        }),*/

];