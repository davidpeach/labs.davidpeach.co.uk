<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GamePlaythroughResource;
use App\Models\Game;
use App\Models\GamePlaythrough;
use App\Models\Song;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class PlaythroughController extends Controller
{
    public function index()
    {
        $playthroughs = GamePlaythrough::orderBy('last_actioned_at', 'desc')->get();

        return GamePlaythroughResource::collection($playthroughs->loadMorph('game', [
            GamePlaythrough::class => 'playthroughs',
        ]));
    }

    public function show(GamePlaythrough $playthrough)
    {
    	return new GamePlaythroughResource($playthrough->load('sessions'));
    }
}
