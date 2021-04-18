<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GamePlaythrough;
use App\Models\GamingSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GamePlaythroughTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_playthrough_will_update_its_last_actioned_at_when_a_new_gaming_session_is_saved()
    {
        $this->be(User::factory()->create());

        $game = Game::factory()->create();
        $playthrough = GamePlaythrough::factory()->for($game)->create([
            'last_actioned_at' => '2021-01-01 18:30:00'
        ]);

        $this->json('POST', '/api/games/' . $game->id . '/playthroughs/' . $playthrough->id . '/sessions', [
            'started_at' => '2021-04-01 12:00:00',
        ]);

        $playthroughLastPlayed = $playthrough->fresh()->last_played_at->format('Y-m-d H:i:s');
        $this->assertEquals('2021-04-01 12:00:00', $playthroughLastPlayed);
    }

    /** @test */
    public function playthroughs_can_be_retrieved_in_order_of_the_last_actioned_at_descending()
    {
        $lastOfUs = Game::factory()->create([
            'title' => 'The Last of Us',
            'image_path' => '/path/to/last-of-us-image.jpeg',
        ]);
        $horizon  = Game::factory()->create([
            'title' => 'Horizon Zero Dawn',
            'image_path' => '/path/to/horizon-image.jpeg',
        ]);

        GamePlaythrough::factory()
            ->times(2)
            ->for($lastOfUs, 'game')
            ->state(new Sequence(
                // id #1
                [
                    'last_actioned_at' => '2020-01-20 12:00:00',
                    'is_complete' => true,
                ],
                // id #2
                [
                    'last_actioned_at' => '2021-01-20 18:00:00',
                    'is_complete' => true,
                ]
            ))
            ->create();

        GamePlaythrough::factory()
            ->times(2)
            ->for($horizon, 'game')
            ->state(new Sequence(
                // id #3
                [
                    'last_actioned_at' => '2020-06-10 12:00:00',
                    'is_complete' => true,
                ],
                // id #4
                [
                    'last_actioned_at' => '2021-04-10 18:00:00',
                    'is_complete' => false,
                ]
            ))
            ->create();

        $response = $this->json('GET', 'api/playthroughs');

        $response->assertJsonFragment([
            'id' => 4,
            'last_played_at' => '10th April 2021 6:00pm',
            'image_path' => 'http://labs.davidpeach.local/storage/path/to/horizon-image.jpeg',
        ]);
        $response->assertJsonFragment([
            'id' => 2,
            'last_played_at' => '20th January 2021 6:00pm',
            'image_path' => 'http://labs.davidpeach.local/storage/path/to/last-of-us-image.jpeg',
        ]);
        $response->assertJsonFragment([
            'id' => 3,
            'last_played_at' => '10th June 2020 12:00pm',
            'image_path' => 'http://labs.davidpeach.local/storage/path/to/horizon-image.jpeg',
        ]);
        $response->assertJsonFragment([
            'id' => 1,
            'last_played_at' => '20th January 2020 12:00pm',
            'image_path' => 'http://labs.davidpeach.local/storage/path/to/last-of-us-image.jpeg',
        ]);

        $response->assertSeeTextInOrder([
            '10th April 2021 6:00pm',
            '20th January 2021 6:00pm',
            '10th June 2020 12:00pm',
            '20th January 2020 12:00pm',
        ]);
    }

    /** @test */
    public function a_single_playthrough_can_be_retrieved_with_all_associated_sessions()
    {
        $lastOfUs = Game::factory()->create([
            'title' => 'The Last of Us',
            'image_path' => '/path/to/last-of-us-image.jpeg',
        ]);

        $playthrough = GamePlaythrough::factory()
            ->for($lastOfUs)
            ->create([
                'title' => 'Grounded Mode run #3'
            ]);

        GamingSession::factory()
            ->for($playthrough)
            ->count(3)
            ->state(new Sequence(
                [
                    'started_at' => '2021-01-20 14:00:00',
                    'finished_at' => '2021-01-20 16:00:00',
                ],
                [
                    'started_at' => '2021-01-21 18:00:00',
                    'finished_at' => '2021-01-21 19:30:00',
                ],
                [
                    'started_at' => '2021-01-25 10:00:00',
                    'finished_at' => '2021-01-25 14:30:00',
                ],
            ))
            ->create();

        $response = $this->json('GET', '/api/playthroughs/' . $playthrough->id);

        $response->assertJson(['data' => [
            'id' => $playthrough->id,
            'title' => 'Grounded Mode run #3',
            'sessions' => [
                [
                    'started_at' => '20th January 2021 2:00pm',
                    'finished_at' => '20th January 2021 4:00pm'
                ],
                [
                    'started_at' => '21st January 2021 6:00pm',
                    'finished_at' => '21st January 2021 7:30pm'
                ],
                [
                    'started_at' => '25th January 2021 10:00am',
                    'finished_at' => '25th January 2021 2:30pm'
                ],
            ],
        ]]);
    }
}
