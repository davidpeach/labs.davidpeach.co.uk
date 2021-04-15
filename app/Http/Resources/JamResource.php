<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class JamResource extends JsonResource
{
    private $types = [
        'App\Models\Song' => 'toSongJamArray',
        'App\Models\Album' => 'toAlbumJamArray',
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = $this->jamable_type;

        return call_user_func_array([$this, $this->types[$type]], []);
    }

    private function toSongJamArray()
    {
        return [
            'subject' => $this->jamable->title,
            'artist' => $this->jamable->album->artist->name,
            'album' => $this->jamable->album->title,
            'published_at' => $this->published_at->format('Y-m-d'),
            'type' => 'song',
            'image' => $this->jamable->album->cover_image ? Storage::url($this->jamable->album->cover_image): '',
        ];
    }

    private function toAlbumJamArray()
    {
        return [
            'subject' => $this->jamable->title,
            'artist' => $this->jamable->artist->name,
            'published_at' => $this->published_at->format('Y-m-d'),
            'type' => 'album',
        ];
    }
}
