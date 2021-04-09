<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
    ];

    public function album()
    {
    	return $this->belongsTo(Album::class);
    }

    public function jams()
    {
    	return $this->morphMany(Jam::class, 'jamable');
    }
}
