<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index(Request $request)
    {
    	$query = $request->get('q');

        $foundAlbums = Album::where('title', 'LIKE', $query . '%')
            ->orderBy('title', 'asc')
            ->get();

        return AlbumResource::collection($foundAlbums);
    }

    public function store(Request $request)
    {
    	$artist = Artist::findOrFail($request->get('artist_id'));

    	$album = Album::make([
    		'title' => $request->get('title'),
    	]);

    	$artist->albums()->save($album);

    	return new AlbumResource($album);
    }
}
