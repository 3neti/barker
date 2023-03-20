<?php

namespace App\Actions;

use Illuminate\Support\Facades\{Gate, Validator};
use Lorisleiva\Actions\Concerns\AsAction;
use Laravel\Jetstream\Jetstream;
use App\Models\{Campaign, User};
use App\Events\AddingCampaign;

class CreateCampaign
{
    use AsAction;

    /**
     * Validate and create a new campaign for the given user.
     *
     * @param  array<string, string>  $input
     */
    public function handle(User $user, array $input): Campaign
    {
//        Gate::forUser($user)->authorize('create', Jetstream::newCampaignModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createCampaign');

        AddingCampaign::dispatch($user);

        $user->switchCampaign($campaign = $user->ownedCampaigns()->create($input));

        return $campaign;
    }
}
