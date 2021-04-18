<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameCoverController extends Controller
{
    public function store(Game $game, Request $request)
    {
    	$game->update([
    		'image_path' => $request->file('image')->storePublicly('game_covers'),
    	]);
    }
}
