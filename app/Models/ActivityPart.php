<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityPart extends Model
{
    use HasFactory;

    public $table = 'activity_parts';

    /**
     * An activity part will belong to a specific activity as many activities
     * are performed over a number of days.
     * e.g. "reading" a Book normally takes days / weeks.
     *
     * @param  string $class The class path to the relationship model
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner(string $class): BelongsTo
    {
    	return $this->belongsTo($class, 'activity_id');
    }
}
