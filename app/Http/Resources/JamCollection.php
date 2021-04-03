<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JamCollection extends ResourceCollection
{

    public $collects = JamResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $jams = $this->collection->groupBy(function($jam) {
            return Carbon::parse($jam->published_at)->format('Y-m-d');
        });

        return [
            'data' => $jams,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
