<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'cover_image',
    ];

    public function artist()
    {
    	return $this->belongsTo(Artist::class);
    }

    public function jams()
    {
    	return $this->morphMany(Jam::class, 'jamable');
    }

    public function songs()
    {
    	return $this->hasMany(Song::class);
    }
}
