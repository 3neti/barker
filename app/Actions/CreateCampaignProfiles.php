<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Events\CampaignAdded;
use App\Models\Campaign;
use App\Classes\Barker;

class CreateCampaignProfiles
{
    use AsAction;

    public function handle(Campaign $campaign, array $profiles): int
    {
        $count = 0;
        foreach ($profiles as $profile) {
            $options = Barker::$profiles[$profile]->options;
            $campaign->campaignProfiles()
                ->create(
                    compact('profile','options')
                ) && $count++;
        }

        return $count;
    }

    public function asListener(CampaignAdded $event)
    {
        $this->handle($event->campaign, $event->profiles);
    }
}
