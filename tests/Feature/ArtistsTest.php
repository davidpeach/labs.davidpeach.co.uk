<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArtistsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function artists_can_be_searched_for_alphabetically_by_name()
    {
        $this->be(User::factory()->create());

        $artistCradle = Artist::factory()->create(['name' => 'Cradle of Filth']);
        $artistBritney = Artist::factory()->create(['name' => 'Britney Spears']);

        $response = $this->json('GET', 'api/artists?q=Britney');

        $response->assertJson(['data' => [
            [
                'text' => 'Britney Spears',
                'value' => $artistBritney->id,
            ],
        ]]);
    }

    /** @test */
    public function artists_can_be_created()
    {
        $this->be(User::factory()->create());

        $response = $this->json('POST', 'api/artists', [
            'name' => 'Britney Spears',
        ]);

        $artist = Artist::first();

        $this->assertDatabaseHas('artists', [
            'name' => 'Britney Spears',
        ]);

        $response->assertJson(['data' => [
            'text' => 'Britney Spears',
            'value' => 1,
        ]]);

        $this->assertEquals('Britney Spears', $artist->name);
    }
}
