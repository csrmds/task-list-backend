<?php

use Illuminate\Support\Facades\Route;

Route::get('/auth/google', [App\Http\Controllers\UserController::class, 'authGoogle']);
Route::get('/auth/callback/google', [App\Http\Controllers\UserController::class, 'authCallback']);

// Route::get('/', function () {
//     return view('welcome');
// });
