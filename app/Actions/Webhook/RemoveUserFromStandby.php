<?php

namespace App\Actions\Webhook;

use App\Actions\Jetstream\RemoveTeamMember;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Events\TeamMemberAssigned;
use App\Models\{Team, User};

class RemoveUserFromStandby
{
    use AsAction;

    public function handle(Team $team, User $user): void
    {
        $system = app(User::class)->system();
        $defaultTeam = app(Team::class)->default();
        app(RemoveTeamMember::class)->remove($system, $defaultTeam, $user);
    }

    public function asListener(TeamMemberAssigned $event)
    {
        $this->handle($event->team, $event->user);
    }
}
