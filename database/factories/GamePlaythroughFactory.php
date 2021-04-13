<?php

namespace Database\Factories;

use App\Models\GamePlaythrough;
use Illuminate\Database\Eloquent\Factories\Factory;

class GamePlaythroughFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GamePlaythrough::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'is_complete' => false,
        ];
    }
}
