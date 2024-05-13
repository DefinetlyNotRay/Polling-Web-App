<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LoginController;

// Import the authentication middleware
use App\Http\Middleware\CheckTokenExpiry;
use Illuminate\Auth\Middleware\Authenticate;

// Define the routes
Route::get("/", [PageController::class, "home"]);

Route::get("/login", [PageController::class, "login"]);

// Define a group for authenticated routes
Route::middleware([Authenticate::class, CheckTokenExpiry::class])->group(function () {
    // Define the routes that require authentication
    // For example, the home route
    Route::get("/", [PageController::class, "home"]);
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
