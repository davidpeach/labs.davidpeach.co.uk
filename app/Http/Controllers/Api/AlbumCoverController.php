<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumCoverController extends Controller
{
    public function store(Album $album, Request $request)
    {
    	$album->update([
    		'cover_image' => $request->file('image')->storePublicly('album_covers'),
    	]);
    }
}
