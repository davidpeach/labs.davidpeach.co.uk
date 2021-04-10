<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AlbumsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function albums_can_be_searched_for_alphabetically_by_title()
    {
        $this->be(User::factory()->create());

        $artist = Artist::factory()->create(['name' => 'Cradle of Filth']);

        $albumA = Album::factory()->for($artist)->create([
            'title' => 'Thornography',
        ]);

        Album::factory()->for($artist)->create([
            'title' => 'Dusk and Her Embrace',
        ]);

        $response = $this->json('GET', 'api/albums?q=Thorn');

        $response->assertJson(['data' => [
            [
                'text' => 'Thornography',
                'value' => $albumA->id,

                'artist' => [
                    'text' => 'Cradle of Filth',
                    'value' => $artist->id,
                ],
            ],
        ]]);
    }

    /** @test */
    public function albums_can_be_created_for_an_artist()
    {
        $this->be(User::factory()->create());
        $artist = Artist::factory()->create([
                    'name' => 'Britney Spears',
                ]);

        $response = $this->json('POST', 'api/albums', [
            'title' => 'Baby one more time',
            'artist_id' => $artist->id
        ]);

        $album = Album::first();

        $this->assertDatabaseHas('albums', [
            'title' => 'Baby one more time',
            'artist_id' => $artist->id,
        ]);

        $response->assertJson(['data' => [
            'text' => 'Baby one more time',
            'value' => $album->id,
            'artist' => [
                'text' => 'Britney Spears',
                'value' => 1,
            ],
        ]]);

        $this->assertEquals('Britney Spears', $album->artist->name);
    }
}
