<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

abstract class Activity extends Model
{
    use HasFactory;

	public $table = 'activities';

    /**
     * An activity can be done over many parts
     * Specific activity types will need to extend this.
     * They can then define their owner child / owner relationship
     * methods and deligate to these generic activity relationships.
     *
     * @param  string $model The class path to the relationship model
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function activityParts(string $model): HasMany
    {
    	return $this->hasMany($model, 'activity_id');
    }

    /**
     * An activity will be performed on a specific owning item.
     * E.g. "playing" a Game; "watching" a Film; "writing" a Post.
     *
     * @param  string $model The class path to the relationship model
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    protected function owner(string $model): MorphTo
    {
    	return $this->morphTo($model, 'activityable_type', 'activityable_id');
    }
}
