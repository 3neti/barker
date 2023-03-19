<?php

namespace App\Actions\Webhook;

use App\Actions\Jetstream\RemoveTeamMember;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Events\NewTeamFromTopup;
use App\Models\{Team, User};

class RemoveUserFromStandby
{
    use AsAction;

    public function handle(User $user, Team $team): void
    {
        $system = app(User::class)->system();
        $defaultTeam = app(Team::class)->default();
        app(RemoveTeamMember::class)->remove($system, $defaultTeam, $user);
    }

    public function asListener(NewTeamFromTopup $event)
    {
        $this->handle($event->user, $event->team);
    }
}
