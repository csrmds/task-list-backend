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


Route::get('/wakeup', function() {
	try {
		$result= DB::select('select now() as now');

		return response()->json([
			'status'=> 'ok',
			'database_time'=> $result[0]->now ?? null
		]);
	} catch(\Exception $e) {
		return response()->json([
			'status'=> 'error',
			'message'=> $e->getMessage()
		], 500);
	}
});

