<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GamePlaythrough;
use App\Models\GamingSession;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GamingTrackerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function i_can_retrieve_the_details_of_a_specific_playthrough_of_a_playstation_game()
    {
        $playThrough = GamePlaythrough::factory()->for(
            Game::factory()->create([
                'title' => 'The Last of Us',
            ])
        )->create();

        GamingSession::factory()
            ->for($playThrough)
            ->count(3)
            ->state(new Sequence(
                [
                    'started_at' => '2021-01-10 20:00:00',
                    'finished_at' => '2021-01-10 21:30:00',
                ],
                [
                    'started_at' => '2021-01-15 08:15:00',
                    'finished_at' => '2021-01-15 11:08:00',
                ],
                [
                    'started_at' => '2021-01-20 12:29:00',
                    'finished_at' => '2021-01-20 15:01:00',
                ]
            ))
            ->create();

        $response = $this->json('GET', '/api/games/1/playthroughs/1');

        $response->assertJson(['data' => [
            'id' => $playThrough->id,
            'game' => [
                'title' => 'The Last of Us',
            ],
            'sessions' => [
                [
                    'started_at' => '10th January 2021 8:00pm',
                    'finished_at' => '10th January 2021 9:30pm',
                ],
                [
                    'started_at' => '15th January 2021 8:15am',
                    'finished_at' => '15th January 2021 11:08am',
                ],
                [
                    'started_at' => '20th January 2021 12:29pm',
                    'finished_at' => '20th January 2021 3:01pm',
                ]
            ],
        ]]);
    }
}
