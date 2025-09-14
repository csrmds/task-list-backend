<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\DB;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/task/list', [App\Http\Controllers\TaskController::class, 'getTasks'])->middleware('auth:sanctum');
Route::post('/task/store', [App\Http\Controllers\TaskController::class, 'store'])->middleware('auth:sanctum');
Route::post('/task/destroy', [App\Http\Controllers\TaskController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/task/update', [App\Http\Controllers\TaskController::class, 'update'])->middleware('auth:sanctum');

Route::post('/auth/login', [App\Http\Controllers\UserController::class, 'login']);
Route::get('/auth/logout', [App\Http\Controllers\UserController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/auth/check', [App\Http\Controllers\UserController::class, 'authCheck'])->middleware('auth:sanctum');

Route::post('/gcalendar/createevent', [App\Http\Controllers\GoogleCalendarController::class, 'createEvent'])->middleware('auth:sanctum');
Route::post('/gcalendar/updateevent', [App\Http\Controllers\GoogleCalendarController::class, 'updateEvent'])->middleware('auth:sanctum');
Route::post('/gcalendar/deleteevent', [App\Http\Controllers\GoogleCalendarController::class, 'deleteEvent'])->middleware('auth:sanctum');


Route::get('/teste', function () {
    try {
		$pdo= getenv();

	    return response()->json($pdo);
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