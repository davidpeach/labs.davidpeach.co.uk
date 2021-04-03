<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JamCollection;
use App\Models\Jam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JamsController extends Controller
{
    public function index()
    {
    	return new JamCollection(Jam::orderBy('published_at', 'desc')->get());
    }

    public function store(Request $request)
    {
    	Jam::create([
    		'song_id' => $request->get('song_id'),
    		'published_at' => $request->get('published_at'),
    	]);
    }
}
