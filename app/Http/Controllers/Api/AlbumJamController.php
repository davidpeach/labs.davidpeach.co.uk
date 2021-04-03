<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Jam;
use Illuminate\Http\Request;

class AlbumJamController extends Controller
{
    public function store(Request $request)
    {
    	$album = Album::find($request->get('album_id'));

    	$jam = Jam::make([
    		'published_at' => $request->get('published_at'),
    	]);

    	$album->jams()->save($jam);
    }
}
