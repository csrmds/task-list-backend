<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/teste', function () {
    try {
		\DB::connection()->getPdo();

		$config= DB::connection()->getConfig();

	    return response()->json($config);
	} catch (\Exception $e) {
		return 'Erro na conexão: ' . $e->getMessage();
	}
});

Route::get('/teste2', function () {
    try {
		$user= User::all();

	    return response()->json($user);
	} catch (\Exception $e) {
		return 'Erro na conexão: ' . $e->getMessage();
	}
});