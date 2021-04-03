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
}
