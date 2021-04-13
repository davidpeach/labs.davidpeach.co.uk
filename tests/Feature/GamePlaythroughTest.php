<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GamePlaythrough;
use App\Models\User;
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
}
