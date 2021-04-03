<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Jam;
use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/** @group jams */
class JamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_all_jams_of_either_albums_or_songs_grouped_by_date_order_newest_to_oldest()
    {
        // Given I have two jams at different dates...
        // ... One which is a "song" jam...
        Jam::factory()
            ->for(
                Song::factory()
                ->for(
                    Album::factory()
                    ->for(
                        Artist::factory()
                        ->create(['name' => 'Britney Spears'])
                    )
                    ->create(['title' => 'Baby one more time'])
                )
                ->create(['title' => 'Baby one more time']),
                'jamable'
            )
            ->create(['published_at' => new Carbon('5th January 2021')]);

        // ... And another which is an "album" jam.
        Jam::factory()
            ->for(
                Album::factory()
                ->for(
                    Artist::factory()
                    ->create(['name' => 'Cradle of Filth'])
                )
                ->create(['title' => 'Nymphetamine']),
                'jamable'
            )
            ->create(['published_at' => new Carbon('10th January 2021')]);

        // When I make a request for all jams.
        $response = $this->json('get', '/api/jams/all');

        // Then I should get both jams grouped by date...
        $response->assertJsonFragment([
            '2021-01-05' => [
                [
                    'album' => 'Baby one more time',
                    'artist' => 'Britney Spears',
                    'published_at' => '2021-01-05',
                    'subject' => 'Baby one more time',
                    'type'=> 'song',
                ],
            ]]);

        $response->assertJsonFragment([
            '2021-01-10' => [
                [
                    'subject' => 'Nymphetamine',
                    'artist' => 'Cradle of Filth',
                    'published_at' => '2021-01-10',
                    'type'=> 'album',
                ],
            ],
        ], $response->getData());

        // ... And they should returned as the newest first.
        $response->assertSeeTextInOrder(['2021-01-10', '2021-01-05']);
    }

    /** @test */
    public function a_jam_can_be_created_for_a_song()
    {
        // Given I have a song
        $song = Song::factory()->create([
            'title' => 'The first song',
        ]);
        $publishedAt = new Carbon('25th December 2020');

        // When I "jam" it
        $this->post('api/jams/songs', [
            'song_id' => $song->id,
            'published_at' => $publishedAt->format('Y-m-d h:i'),
        ]);

        // Then it should show up in the jams table
        $this->assertDatabaseHas('jams', [
            'jamable_id' => $song->id,
            'jamable_type' => 'App\Models\Song',
            'published_at' => $publishedAt->format('Y-m-d h:i:s'),
        ]);
    }

    /** @test */
    public function a_jam_can_be_created_for_an_album()
    {
        $this->w();
        // Given I have a song
        $album = Album::factory()->create([
            'title' => 'The bestest album',
        ]);
        $publishedAt = new Carbon('25th December 2020');

        // When I "jam" it
        $this->post('api/jams/albums', [
            'album_id' => $album->id,
            'published_at' => $publishedAt->format('Y-m-d h:i'),
        ]);

        // Then it should show up in the jams table
        $this->assertDatabaseHas('jams', [
            'jamable_id' => $album->id,
            'jamable_type' => 'App\Models\Album',
            'published_at' => $publishedAt->format('Y-m-d h:i:s'),
        ]);
    }
}
