<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jam extends Model
{
    use HasFactory;

    protected $fillable = [
    	'song_id',
    	'published_at',
    ];

    protected $dates = [
    	'published_at',
    ];

    public function song()
    {
    	return $this->belongsTo(Song::class);
    }

    public function jamable()
    {
    	return $this->morphTo();
    }
}
