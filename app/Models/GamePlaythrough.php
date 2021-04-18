<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GamePlaythrough extends Activity
{
    protected $fillable = [
        'last_actioned_at',
    ];

    protected $dates = [
        'last_actioned_at',
    ];

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
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function game(): MorphTo
    {
        return $this->owner(Game::class);
    }

    public function getDetermineStartedAtAttribute()
    {
        $session = $this->sessions()
            ->orderBy('started_at', 'asc')
            ->first();

        if (is_null($session)) {
            return null;
        }

        return $session->started_at;
    }

    public function getDetermineFinishedAtAttribute()
    {
        if (!$this->is_complete) {
            return null;
        }

        $session = $this->sessions()
            ->orderBy('started_at', 'desc')
            ->first();

        if (is_null($session)) {
            return null;
        }

        return $session->finished_at;
    }

    public function getDeterminePlaytimeRangeAttribute()
    {
        $range = '';

        if (is_null($this->determine_started_at)) {
            return 'Not yet started';
        }

        if (is_null($this->determine_finished_at)) {
            return 'Started ' . $this->determine_started_at->format('jS F Y') . ', still going.';
        }

        return $this->determine_started_at->format('jS F Y') . ' to ' . $this->determine_finished_at->format('jS F Y');
    }

    /**
     * A better-named accessor for the underlying activity table column name
     * @return \Carbon\Carbon
     */
    public function getLastPlayedAtAttribute(): Carbon
    {
        return $this->last_actioned_at;
    }

    public function addSession(string $startedAt)
    {
        $session = GamingSession::make(['started_at' => $startedAt]);

        return $this->sessions()->save($session);
    }
}
