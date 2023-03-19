<?php

namespace App\Actions\Webhook;

use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Actions\Jetstream\CreateTeam;
use Laravel\Jetstream\Jetstream;
use App\Events\NewTeamFromTopup;
use Illuminate\Support\Arr;
use App\Models\User;

class AssignUserTeam
{
    use AsAction;

    public function handle(array $attribs): void
    {
        Validator::make($attribs, [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
            'team' => ['string', 'max:255', 'unique:teams,name'],
        ])->validate();

        $user = Jetstream::findUserByEmailOrFail(Arr::get($attribs, 'email'));

        optional(Arr::get($attribs, 'team'), function ($name) use ($user) {
            $team = app(CreateTeam::class)->create($user, compact('name'));
            $team->users()->attach($user, User::adminRoleAttribute());
            $user->switchTeam($team);
            event(new NewTeamFromTopup($user, $team));
        });
    }

    public function asJob(array $attribs)
    {
        $this->handle($attribs);
    }
}
