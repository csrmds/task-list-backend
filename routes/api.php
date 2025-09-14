<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

Route::get('/teste3', function () {
    try {
		$dataBase= DB::connection()->getDatabaseName();
		//$x= DB::connection()->getConfig();

		$envVars= [
			'dbConnection'=> env('DB_CONNECTION'),
			'dbHost'=> env('DB_HOST'),
			'dbPort'=> env('DB_PORT'),
			'dbDatabase'=> env('DB_DATABASE'),
			'dbUser'=> env('DB_USERNAME'),
		];

		//$connection= \DB::connection()->getDatabaseName();

	    return response()->json([$envVars, $dataBase]);
	} catch (\Exception $e) {
		return 'Erro na conexão: ' . $e->getMessage();
	}
});