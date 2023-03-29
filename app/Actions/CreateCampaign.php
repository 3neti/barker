<?php

namespace App\Actions;

use Illuminate\Support\Facades\{Gate, Validator};
use App\Events\{AddingCampaign, CampaignAdded};
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\{Campaign, User};
use Illuminate\Support\Arr;
use App\Classes\Barker;
use App\Rules\Type;

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
        Validator::make($input, $this->rules())->validateWithBag('createCampaign');

        AddingCampaign::dispatch($owner);

        $campaign = app(Campaign::class)->make($input);
        $campaign->owner()->associate($owner);
        $team = $owner->currentTeam;
        $campaign->team()->associate($team)->save();
        $team->switchCampaign($campaign);
        $owner->switchCampaign($campaign);
        $type = Arr::get($input, 'type');

        $channels = Arr::only($input, Barker::$channels);
        CampaignAdded::dispatch($owner, $campaign, $type, $channels);

        return $campaign;
    }

    public function asJob(User $owner, array $input)
    {
        $this->handle($owner, $input);
    }

    protected function rules(): array
    {
        return array_filter([
            'email' => ['nullable', 'email'],
            'type' => Barker::hasTypes()
                ? ['required', 'string', new Type]
                : null,
        ]);
    }
}
