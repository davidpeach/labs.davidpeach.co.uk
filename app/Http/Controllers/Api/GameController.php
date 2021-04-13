<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayedGameResource;
use App\Models\Game;
use App\Models\GamePlaythrough;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $played = $request->query('played');

        $games = Game::orderByDesc(GamePlaythrough::select('last_actioned_at')
            ->whereColumn('activities.activityable_id', 'games.id')
            ->latest('last_actioned_at')
            ->take(1)
        )->get();

        return PlayedGameResource::collection($games);
    }
}
