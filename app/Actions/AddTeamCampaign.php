<?php

namespace App\Actions;

use App\Events\{AddingTeamCampaign, CampaignAdded, TeamCampaignAdded};
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\{Campaign, Team};

class AddTeamCampaign
{
    use AsAction;

    public function handle(Team $team, Campaign $campaign, string $role)
    {
        AddingTeamCampaign::dispatch($team, $campaign);
        $campaign->teams()->attach(
            $team, ['role' => $role]
        );
        TeamCampaignAdded::dispatch($team, $campaign);
    }

    public function asJob(Team $team, Campaign $campaign, string $role)
    {
        $this->handle($team, $campaign, $role);
    }

    public function asListener(CampaignAdded $event)
    {
        $this->handle($event->owner->currentTeam, $event->campaign, $event->role);
    }
}
