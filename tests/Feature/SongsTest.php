<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SongsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function songs_can_be_searched_for_by_title_returned_alphabetically()
    {
        $songA = Song::factory()
            ->for(
                Album::factory()
                ->for(
                    Artist::factory()
                    ->create(['name' => 'Britney Spears'])
                )
                ->create(['title' => 'Baby one more time'])
            )
            ->create(['title' => 'Baby one more time']);

        $songB = Song::factory()
            ->for(
                Album::factory()
                ->for(
                    Artist::factory()
                    ->create(['name' => 'An Artist'])
                )
                ->create(['title' => 'Some Album'])
            )
            ->create(['title' => 'Baby baby']);

        $response = $this->json('GET', '/api/songs?q=baby');

        $response->assertJson(['data' => [
            [
                'title' => 'Baby baby',
                'album' => 'Some Album',
                'artist' => 'An Artist',
                'id' => $songB->id,
            ],
            [
                'title' => 'Baby one more time',
                'album' => 'Baby one more time',
                'artist' => 'Britney Spears',
                'id' => $songA->id,
            ],
        ]]);
    }
}
