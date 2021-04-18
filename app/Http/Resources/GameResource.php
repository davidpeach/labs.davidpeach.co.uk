<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GameResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'image_path' => $this->image_path ? Storage::url($this->image_path): '',
            'capture_count' => 0,
            'playthrough_count' => 0,
            'playthroughs' => GamePlaythroughResource::collection($this->whenLoaded('playthroughs')),
        ];
    }
}
