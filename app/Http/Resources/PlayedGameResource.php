<?php

namespace App\Http\Resources;

use App\Http\Resources\GamePlayedPlaythroughResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayedGameResource extends JsonResource
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
            'capture_count' => 0,
            'playthrough_count' => 0,
            'playthroughs' => GamePlayedPlaythroughResource::collection($this->playthroughs)
        ];
    }
}
