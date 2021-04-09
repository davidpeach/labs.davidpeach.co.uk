<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SongResource;
use App\Models\Album;
use App\Models\Song;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');

        $foundSongs = Song::where('title', 'LIKE', $query . '%')
            ->orderBy('title', 'asc')
            ->get();

        return SongResource::collection($foundSongs);
    }

    public function store(Request $request)
    {
    	$album = Album::findOrFail($request->get('album_id'));

    	$song = Song::make([
    		'title' => $request->get('title'),
    	]);

    	$album->songs()->save($song);

    	return new SongResource($song);
    }
}
