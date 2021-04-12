<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GamingSession extends ActivityPart
{
    protected $dates = [
    	'started_at',
    	'finished_at',
    ];

    /**
     * A gaming session will be part of a single playthrough.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gamePlaythrough(): BelongsTo
    {
    	return $this->owner(GamePlaythrough::class);
    }
}
