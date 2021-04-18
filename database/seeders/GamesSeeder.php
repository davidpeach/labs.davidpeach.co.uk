<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GamePlaythrough;
use App\Models\GamingSession;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = Game::factory()
            ->times(5)
            ->sequence(
                ['title' => 'The Last of Us part 2'],
                ['title' => 'Dead by Daylight'],
                ['title' => 'Resident Evil 2'],
                ['title' => 'Horizon Zero Dawn'],
                ['title' => 'Days Gone'],
            )
            ->create();

        $games->each(function (Game $game) {
            $playthrough = GamePlaythrough::factory()->make(['title' => 'Another playthrough for ' . $game->title]);
            $game->playthroughs()->save($playthrough);
        });

        $datetime = new Carbon('2021-02-01 14:00:00');
        GamePlaythrough::all()->each(function (GamePlaythrough $playthrough) use ($datetime) {

            $sessions = GamingSession::factory()
                ->times(3)
                ->sequence(
                    ['started_at' => $datetime, 'finished_at' => $datetime->addHours(2)],
                    ['started_at' => $datetime->addDays(1), 'finished_at' => $datetime->addHours(1)],
                    ['started_at' => $datetime->addDays(1), 'finished_at' => $datetime->addHours(4)],
                )
                ->make();

            $sessions->each(function (GamingSession $session) use ($playthrough) {
                $playthrough->sessions()->save($session);
            });

            $datetime->addDays(3);
        });
    }
}
