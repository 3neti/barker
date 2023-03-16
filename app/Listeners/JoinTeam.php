<?php

namespace App\Listeners;

use Junges\InviteCodes\Events\InviteRedeemedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Actions\Jetstream\AddTeamMember;
use Laravel\Jetstream\Jetstream;
use App\Models\{Team, User};

class JoinTeam implements ShouldQueue
{
    protected array $arguments;

    public function handle(InviteRedeemedEvent $event): void
    {
        $this->setParameters($event)
            ->addToTeam()
            ->switchToTeam();
    }

    protected function setParameters(InviteRedeemedEvent $event): self
    {
        $user = User::findOrFail($event->invite->data->from_id);
        $team = Team::where('id', $event->invite->data->team_id)->firstOrFail();
        $email = $event->invite->to;
        $role = $event->invite->data->role;
        $this->arguments = compact('user', 'team', 'email', 'role');

        return $this;
    }

    protected function addToTeam(): self
    {
        app(AddTeamMember::class)->add(...$this->arguments);

        return $this;
    }

    protected function switchToTeam(): self
    {
        $user = Jetstream::findUserByEmailOrFail($this->arguments['email']);
        $user->switchTeam($this->arguments['team']);

        return $this;
    }
}
