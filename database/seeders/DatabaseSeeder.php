<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::factory()->create([
        	'name' => env('MY_NAME'),
        	'email' => env('MY_EMAIL'),
        	'password' => bcrypt(env('MY_PASSWORD')),
        ]);

        $token = $user->createToken('testing-token');
        \Log::info('Testing token: ' . $token->plainTextToken);
    }
}
