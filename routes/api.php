<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\DB;


Route::middleware('auth:sanctum')->group(function (){
	
	Route::get('/user', function (Request $request) {
		return $request->user();
	});

	Route::get('/task/list', [App\Http\Controllers\TaskController::class, 'getTasks']);
	Route::post('/task/store', [App\Http\Controllers\TaskController::class, 'store']);
	Route::post('/task/destroy', [App\Http\Controllers\TaskController::class, 'destroy']);
	Route::post('/task/update', [App\Http\Controllers\TaskController::class, 'update']);
	
	Route::get('/auth/logout', [App\Http\Controllers\UserController::class, 'logout']);
	Route::get('/auth/check', [App\Http\Controllers\UserController::class, 'authCheck']);

	Route::post('/gcalendar/createevent', [App\Http\Controllers\GoogleCalendarController::class, 'createEvent']);
	Route::post('/gcalendar/updateevent', [App\Http\Controllers\GoogleCalendarController::class, 'updateEvent']);
	Route::post('/gcalendar/deleteevent', [App\Http\Controllers\GoogleCalendarController::class, 'deleteEvent']);

});


Route::post('/auth/login', [App\Http\Controllers\UserController::class, 'login']);


Route::get('/wakeup', function() {
	try {
		//$result= DB::select('select now() as now');
		$result= DB::select('select count(*), now() from migrations');

		return response()->json([
			'status'=> 'ok',
			'response'=> $result[0] ?? null
		]);
	} catch(\Exception $e) {
		return response()->json([
			'status'=> 'error',
			'message'=> $e->getMessage()
		], 500);
	}
});

