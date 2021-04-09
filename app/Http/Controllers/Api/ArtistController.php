<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArtistResource;
use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index(Request $request)
    {
    	$query = $request->get('q');

        $foundArtists = Artist::where('name', 'LIKE', $query . '%')
            ->orderBy('name', 'asc')
            ->get();

        return ArtistResource::collection($foundArtists);
    }

    public function store(Request $request)
    {
    	$artist = Artist::create([
    		'name' => $request->get('name'),
    	]);

    	return new ArtistResource($artist);
    }
}
