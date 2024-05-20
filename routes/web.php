<?php

use App\Http\Middleware\CheckIfAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

// Import the authentication middleware
use App\Http\Controllers\PollController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\CheckTokenExpiry;
use Illuminate\Auth\Middleware\Authenticate;

// Define the routes
Route::get("/", [PageController::class, "home"]);

Route::get("/login", [PageController::class, "login"]);
Route::get('/register', [PageController::class, "register"]);

// Define a group for authenticated routes
Route::middleware([Authenticate::class, CheckTokenExpiry::class])->group(function () {
    // Define the routes that require authentication
    // For example, the home route
    Route::get("/", [PageController::class, "home"]);

    /*
    Part of VGJR
    */

    // For User - polls page
    Route::get('/poll', [PageController::class, "user_showpoll"])->name('userpoll');
    Route::get('/admin/poll', [PageController::class, "admin_showpoll"])->name('adminpoll');
    Route::post('/poll/vote', [PollController::class, 'vote']);
    Route::post('/poll/vote/user', [PollController::class, 'voteUser']);
});


// Route for handling login form submission
Route::post('/login/auth/login', [LoginController::class, "index"]);
Route::post('/login/auth/register', [LoginController::class, "indexRegister"]);

Route::get('/logout', [LoginController::class, "logout"]);

// Route for redirecting users to the login page if they are not authenticated
Route::get('/unauthenticated', function () {
    return redirect('/login');
})->name('login');