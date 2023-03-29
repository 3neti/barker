<?php

namespace App\Actions;

use App\Events\{AddingTeamCampaign, CampaignAdded, TeamCampaignAdded};
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\{Campaign, Team};

class AddTeamCampaign
{
    use AsAction;

    /** TODO: add validation */
    public function handle(Team $team, Campaign $campaign, string $type)
    {
        AddingTeamCampaign::dispatch($team, $campaign);
        $campaign->teams()->attach(
            $team, ['type' => $type]
        );
        $team->switchCampaign($campaign);
        TeamCampaignAdded::dispatch($team, $campaign);
    }

    public function asJob(Team $team, Campaign $campaign, string $type)
    {
        $this->handle($team, $campaign, $type);
    }

    public function asListener(CampaignAdded $event)
    {
        $this->handle($event->owner->currentTeam, $event->campaign, $event->type);
    }
}
