<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GamingSessionResource extends JsonResource
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
            'started_at' => $this->started_at->format('jS F Y g:ia'),
            'finished_at' => $this->finished_at->format('jS F Y g:ia'),
        ];
    }
}
