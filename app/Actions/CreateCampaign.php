<?php

namespace App\Actions;

use Illuminate\Support\Facades\{Gate, Validator};
use App\Events\{AddingCampaign, CampaignAdded};
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\{Campaign, User};
use Illuminate\Support\Arr;

class CreateCampaign
{
    use AsAction;

    /**
     * Validate and create a team campaign for the given user.
     *
     * @param array<string, string> $input
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(User $owner, array $input): Campaign
    {
//        Gate::forUser($user)->authorize('create', Jetstream::newCampaignModel());
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:campaigns,name'],
        ])->validateWithBag('createCampaign');

        AddingCampaign::dispatch($owner);

        $campaign = app(Campaign::class)->make($input);
        $campaign->owner()->associate($owner);
        $team = $owner->currentTeam;
        $campaign->team()->associate($team)->save();
        $team->switchCampaign($campaign);
        $owner->switchCampaign($campaign);
        $role = Arr::get($input, 'role', config('domain.defaults.campaign.role', 'agent'));

        CampaignAdded::dispatch($owner, $campaign, $role);

        return $campaign;
    }

    public function asJob(User $owner, array $input)
    {
        $this->handle($owner, $input);
    }
}
