<?php

namespace Database\Seeders;

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
    }
}
