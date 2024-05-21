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

Route::get("/login", [PageController::class, "login"]);
Route::get('/register', [PageController::class, "register"]);
Route::middleware([Authenticate::class, CheckTokenExpiry::class])->group(function () {

    Route::get('/poll', [PageController::class, "user_showpoll"])->name('userpoll');
    Route::get("/", [PageController::class, "home"]);
    Route::post('/poll/vote/user', [PollController::class, 'voteUser']);
});
// Define a group for authenticated routes
Route::middleware([Authenticate::class, CheckTokenExpiry::class, CheckIfAdmin::class])->group(function () {
    Route::get('/admin/poll', [PageController::class, "admin_showpoll"])->name('adminpoll');
    Route::post('/poll/vote', [PollController::class, 'vote']);
    
    Route::get('/admin/poll/create', [PageController::class, "admin_screatepoll"])->name('screatepoll');
    Route::post('/create/poll', [PageController::class, "admin_createpoll"])->name('createpoll');
    Route::get('/poll/delete/{id}', [PollController::class, "delete"]);
});


// Route for handling login form submission
Route::post('/login/auth/login', [LoginController::class, "index"]);
Route::post('/login/auth/register', [LoginController::class, "indexRegister"]);


Route::get('/logout', [LoginController::class, "logout"])->name('logout');

// Route for redirecting users to the login page if they are not authenticated
Route::get('/unauthenticated', function () {
    return redirect('/login');
})->name('login');