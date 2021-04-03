<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;

class SongFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Song::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'album_id' => function () {
                return Album::factory()->create()->id;
            }
        ];
    }
}
