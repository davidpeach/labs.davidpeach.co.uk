<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\GamePlaythrough;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $played = $request->query('played');

        $games = Game::orderByDesc(GamePlaythrough::select('last_actioned_at')
            ->whereColumn('activities.activityable_id', 'games.id')
            ->latest('last_actioned_at')
            ->take(1)
        )->get()->load('playthroughs');

        return GameResource::collection($games);
    }

    public function store(Request $request)
    {
        $data = [
            'title' => $request->title,
        ];

        if ($request->has('image')) {
            $data['image_path'] = $request->file('image')->storePublicly('covers');
        }

        $newGame = Game::create($data);

        return new GameResource($newGame);
    }
}
