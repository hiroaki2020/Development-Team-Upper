<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'DevCheers',
            'email' => config('admin.email'),
            'email_verified_at' => now(),
            'password' => config('admin.password'),
            'remember_token' => Str::random(10),
            'description' => 'DevCheers\' Official Account',
            'url' => null,
        ]);

        $user->switchTeam($user->ownedTeams()->create([
            'name' => 'DevCheers\' Team',
            'description' => 'DevCheers\' Official Team',
            'wanted' => true,
        ]));
    }
}
