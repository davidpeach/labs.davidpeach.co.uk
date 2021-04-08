<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SongResource extends JsonResource
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
            'text' => $this->title . ' by ' . $this->album->artist->name,
            'album' => $this->album->title,
            'artist' => $this->album->artist->name,
            'value' => $this->id,
            'title' => $this->title,
            'disabled' => false,
        ];
    }
}
