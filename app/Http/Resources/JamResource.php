<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JamResource extends JsonResource
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
            'song' => $this->song->title,
            'artist' => $this->song->album->artist->name,
            'album' => $this->song->album->title,
            'published_at' => $this->published_at->format('Y-m-d'),
        ];
    }
}
