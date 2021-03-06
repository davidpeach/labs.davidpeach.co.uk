<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
    ];

    public function playthroughs()
    {
        return $this->morphMany(GamePlaythrough::class, 'activityable');
    }
}
