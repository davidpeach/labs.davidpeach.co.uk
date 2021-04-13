<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GamingSessionResource;
use App\Models\Game;
use App\Models\GamePlaythrough;
use Illuminate\Http\Request;

class GamePlaythroughSessionController extends Controller
{
    public function store(Game $game, GamePlaythrough $playthrough, Request $request)
    {
    	$session = $playthrough->addSession($request->get('started_at'));

    	return new GamingSessionResource($session);
    }
}
