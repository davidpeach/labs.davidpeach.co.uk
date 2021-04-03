<?php

use App\Http\Controllers\Api\AlbumJamController;
use App\Http\Controllers\Api\JamsController;
use App\Http\Controllers\Api\SongJamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('jams/all', [JamsController::class, 'index']);
Route::post('jams/songs', [SongJamController::class, 'store']);
Route::post('jams/albums', [AlbumJamController::class, 'store']);



Route::middleware('web')->post('login', function (Request $request) {
	$creds = $request->only('email', 'password');

	if (! auth()->attempt($creds)) {
		throw ValidationException::withMessages([
			'email' => 'Unknown person.',
		]);
	}

	$request->session()->regenerate();

	return response()->json(null, 201);
});

Route::middleware('web')->post('logout', function (Request $request) {
	auth()->guard('web')->logout();

	$request->session()->invalidate();
	$request->session()->regenerateToken();

	return response()->json(null, 200);
});