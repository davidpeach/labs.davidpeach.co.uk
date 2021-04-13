<?php

use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\AlbumJamController;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\GamePlaythroughController;
use App\Http\Controllers\Api\JamsController;
use App\Http\Controllers\Api\SongController;
use App\Http\Controllers\Api\SongJamController;
use App\Http\Controllers\Api\GamePlaythroughSessionController;
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
Route::middleware('auth:sanctum')->post('jams/songs', [SongJamController::class, 'store']);
Route::middleware('auth:sanctum')->post('jams/albums', [AlbumJamController::class, 'store']);

Route::middleware('auth:sanctum')->get('songs', [SongController::class, 'index']);
Route::middleware('auth:sanctum')->post('songs', [SongController::class, 'store']);

Route::middleware('auth:sanctum')->get('albums', [AlbumController::class, 'index']);
Route::middleware('auth:sanctum')->post('albums', [AlbumController::class, 'store']);

Route::middleware('auth:sanctum')->get('artists', [ArtistController::class, 'index']);
Route::middleware('auth:sanctum')->post('artists', [ArtistController::class, 'store']);

Route::get('games/{game}/playthroughs/{playthrough}', [GamePlaythroughController::class, 'show']);
Route::get('games', [GameController::class, 'index']);

Route::post('games/{game}/playthroughs/{playthrough}/sessions', [GamePlaythroughSessionController::class, 'store']);



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