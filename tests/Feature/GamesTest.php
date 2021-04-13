<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GamePlaythrough;
use App\Models\GamingSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GamesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_games_can_be_created_by_me()
    {
        $this->be(User::factory()->create());

        $this->json('POST', 'api/games', [
            'title' => 'The Last of Us',
        ]);

        $this->assertDatabaseHas('games', [
            'title' => 'The Last of Us',
        ]);
    }

    /** @test */
    public function guests_cannot_create_games()
    {
        $response = $this->json('POST', 'api/games', [
            'title' => 'The Last of Us',
        ]);

        $response->assertUnauthorized();
    }

    /** @test */
    public function i_can_get_all_games_that_i_have_played_at_least_once_in_reverse_order_they_were_played()
    {
        $horizon   = Game::factory()->create(['title' => 'Horizon Zero Dawn']);
        $lastOfUs  = Game::factory()->create(['title' => 'The Last of Us']);
        $deathStranding  = Game::factory()->create(['title' => 'Death Stranding']);

        $lastOfUsPlaythrough = GamePlaythrough::factory()->for($lastOfUs)->create([
            'title' => 'Grounded mode attempt',
        ]);

        $horizonPlaythrough = GamePlaythrough::factory()->for($horizon)->create([
            'title' => 'Easy for photomode',
            'is_complete' => true
        ]);

        $deathStrandingPlaythrough = GamePlaythrough::factory()->for($deathStranding)->create([
            'title' => 'First Play',
        ]);

        GamingSession::factory()->for($lastOfUsPlaythrough)->create([
            'started_at' => new Carbon('5th January 2021 8pm'),
        ]);
        GamingSession::factory()->for($horizonPlaythrough)->create([
            'started_at' => new Carbon('15th January 2021 8pm'),
            'finished_at' => new Carbon('15th January 2021 8pm'),
        ]);
        GamingSession::factory()->for($deathStrandingPlaythrough)->create([
            'started_at' => new Carbon('10th January 2021 8pm'),
        ]);

        $response = $this->json('GET', '/api/games?played=true');

        $response->assertJson(['data' => [
            [
                'title' => 'Horizon Zero Dawn',
                'capture_count' => 0,
                'playthrough_count' => 0,
                'playthroughs' => [
                    [
                        'title' => 'Easy for photomode',
                        'started_at' => '15th January 2021 8:00pm',
                        'finished_at' => '15th January 2021 8:00pm',
                        'last_played_at' => '15th January 2021 8:00pm',
                        'is_complete' => true,
                    ]
                ]
            ],
            [
                'title' => 'Death Stranding',
                'capture_count' => 0,
                'playthrough_count' => 0,
                'playthroughs' => [
                    [
                        'title' => 'First Play',
                        'started_at' => '10th January 2021 8:00pm',
                        'finished_at' => null,
                        'last_played_at' => '10th January 2021 8:00pm',
                        'is_complete' => false,
                    ]
                ]
            ],
            [
                'title' => 'The Last of Us',
                'capture_count' => 0,
                'playthrough_count' => 0,
                'playthroughs' => [
                    [
                        'title' => 'Grounded mode attempt',
                        'started_at' => '5th January 2021 8:00pm',
                        'finished_at' => null,
                        'last_played_at' => '5th January 2021 8:00pm',
                        'is_complete' => false,
                    ]
                ]
            ],
        ]]);
    }
}
