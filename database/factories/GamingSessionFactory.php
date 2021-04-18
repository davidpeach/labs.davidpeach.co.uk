<?php

namespace Database\Factories;

use App\Models\GamingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class GamingSessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GamingSession::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'started_at' => '2021-02-01 14:00:00',
            'finished_at' => '2021-02-01 16:00:00',
        ];
    }
}
