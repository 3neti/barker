<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\{Gate, Validator};
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;
use App\Models\{Team, User};

class CreateTeam implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  array<string, string>  $input
     */
    public function create(User $user, array $input): Team
    {
        Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'alias' => ['nullable', 'string', 'max:12'],
        ])->validateWithBag('createTeam');

        AddingTeam::dispatch($user);

        $user->switchTeam($team = $user->ownedTeams()->create($input));

        return $team;
    }
}
