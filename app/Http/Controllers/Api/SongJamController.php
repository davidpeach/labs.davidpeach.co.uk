<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jam;
use App\Models\Song;
use Illuminate\Http\Request;

class SongJamController extends Controller
{
    public function store(Request $request)
    {
    	$song = Song::find($request->get('song_id'));

    	$jam = Jam::make([
    		'published_at' => $request->get('published_at'),
    	]);

    	$song->jams()->save($jam);
    }
}
