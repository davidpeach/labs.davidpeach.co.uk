<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Models\GamePlaythrough;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamePlaythroughTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function it_can_add_a_new_gaming_session()
    {
    	$playthrough = GamePlaythrough::factory()->for(Game::factory()->create())->create();
    
    	$startedAt = '2021-04-01 20:00:00';
    	$playthrough->addSession($startedAt);
    
    	$this->assertCount(1, $playthrough->sessions);
    }
}
