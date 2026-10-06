<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/users');
Route::resource('users', UserController::class)->except(['show']);