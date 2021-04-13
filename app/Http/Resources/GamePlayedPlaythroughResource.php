<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GamePlayedPlaythroughResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'started_at' => $this->determine_started_at ? $this->determine_started_at->format('jS F Y g:ia'): null,
            'finished_at' => $this->determine_finished_at ? $this->determine_finished_at->format('jS F Y g:ia'): null,
            'last_played_at' => $this->last_played_at->format('jS F Y g:ia'),
            'is_complete' => $this->is_complete,
        ];
    }
}
