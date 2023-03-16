<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\{DB, Hash};
use App\Actions\Jetstream\CreateTeam;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Arr;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $attribs = config('domain.seeds.user.system');

        return DB::transaction(function () use ($attribs) {
            return tap(User::create([
                'name' => $attribs['name'],
                'email' => $attribs['email'],
                'password' => Hash::make($attribs['password']),
            ]), function (User $user) {
                $team = app(CreateTeam::class)->create($user, config('domain.seeds.team.default'));
                $user->ownedTeams()->save($team);
                $team->users()->attach($user, ['role' => config('domain.seeds.user.system.role')]);
                $user->switchTeam($team);
            });
        });
    }
}
