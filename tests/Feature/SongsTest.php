<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;
use App\Models\User;
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
                'text' => 'Baby baby',
                'value' => $songB->id,

                'album' => [
                    'text' => 'Some Album',
                    'value' => 2,

                    'artist' => [
                        'text' => 'An Artist',
                        'value' => 2,
                    ],
                ],
            ],
            [
                'text' => 'Baby one more time',
                'value' => $songA->id,
                'disabled' => false,

                'album' => [
                    'text' => 'Baby one more time',
                    'value' => 1,

                    'artist' => [
                        'text' => 'Britney Spears',
                        'value' => 1,
                    ],
                ],
            ],
        ]]);
    }

    /** @test */
    public function songs_can_be_created_for_an_album()
    {
        $this->w();
        $this->be(User::factory()->create());
        $album = Album::factory()
            ->for(Artist::factory()->create([
                'name' => 'Britney Spears',
            ]))
            ->create(['title' => 'Baby one more time']);

        $response = $this->json('POST', 'api/songs', [
            'title' => 'Sometimes',
            'album_id' => $album->id
        ]);

        $song = Song::first();
        $this->assertDatabaseHas('songs', [
            'title' => 'Sometimes',
            'album_id' => $album->id,
        ]);

        $response->assertJson(['data' => [
            'text' => 'Sometimes',
            'value' => $song->id,
            'disabled' => false,
            'album' => [
                'text' => 'Baby one more time',
                'value' => $album->id,
                'artist' => [
                    'text' => 'Britney Spears',
                    'value' => 1,
                ],
            ],
        ]]);

        $this->assertEquals('Baby one more time', $song->album->title);
    }
}
