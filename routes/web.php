<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route for handling login form submission
Route::post('/login/auth/login', [LoginController::class, "index"]);
Route::get('/logout', [LoginController::class, "logout"]);

// Route for redirecting users to the login page if they are not authenticated
Route::get('/unauthenticated', function () {
    return redirect('/login');
})->name('login');

/*
Part of VGJR
*/

// For User - polls page
Route::get('/poll', [PageController::class, "user_showpoll"]);