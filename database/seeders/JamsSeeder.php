<?php

namespace Database\Seeders;

use App\Models\Jam;
use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class JamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Song::factory()
        	->times(5)
        	->state(new Sequence(
        		['title' => 'Best song ever'],
        		['title' => 'Bestest song in the world'],
        		['title' => 'This is the best one'],
        		['title' => 'Rubbish song'],
        		['title' => 'Not a great track'],
        	))
        	->create();

        $songs = Song::all();
        $publishedAt = now();

        $songs->each(function ($song) use ($publishedAt){
            $publishedAt->subDays(1);
            $jams = Jam::factory()->times(5)->make([
                'published_at' => $publishedAt,
            ]);

            $jams->each(function ($jam) use ($song) {
                $song->jams()->save($jam);
            });
        });
    }
}
