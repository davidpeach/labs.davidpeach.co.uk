<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GamePlaythroughResource;
use App\Models\Game;
use App\Models\GamePlaythrough;
use Illuminate\Http\Resources\Json\JsonResource;

class GamePlaythroughController
{
	/**
	 * Get a single game playthrough.
	 *
	 * @param  Game            $game
	 * @param  GamePlaythrough $playthrough
	 * @return \Illuminate\Http\Resources\Json\JsonResource
	 */
    public function show(Game $game, GamePlaythrough $playthrough): JsonResource
    {
    	return new GamePlaythroughResource($playthrough->load('sessions')->loadMorph('game', [
    		GamePlaythrough::class => 'playthroughs',
    	]));
    }
}
