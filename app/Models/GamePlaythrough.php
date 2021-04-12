<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GamePlaythrough extends Activity
{
	/**
	 * A single playthrough of a Game can be split over multiple sessions.
	 * Especially if it's Persona 5!
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function sessions(): HasMany
    {
    	return $this->activityParts(GamingSession::class);
    }

    /**
     * A playthrough will belong to a single game.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game(): BelongsTo
    {
    	return $this->owner(Game::class);
    }
}
