<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Jam;
use App\Models\Song;
use Illuminate\Http\Request;

class SongJamController extends Controller
{
    public function store(Request $request)
    {
    	if ($request->has('song_id')) {
	    	$song = Song::findOrFail($request->get('song_id'));
    	} else {
    		$album = Album::findOrFail($request->get('album_id'));
    		$song = Song::make([
    			'title' => $request->get('song_title'),
    		]);
    		$album->songs()->save($song);
    	}

    	$jam = Jam::make([
    		'published_at' => $request->get('published_at') ?? now(),
    	]);

    	$song->jams()->save($jam);
    }
}
