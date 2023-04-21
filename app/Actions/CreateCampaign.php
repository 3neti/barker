<?php

namespace App\Actions;

use Illuminate\Support\Facades\{Gate, Validator};
use App\Events\{AddingCampaign, CampaignAdded};
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\DB;
use App\Models\{Campaign, User};
use Illuminate\Support\Arr;
use App\Classes\Barker;
use App\Rules\Type;

use Illuminate\Validation\Rule;

class CreateCampaign
{
    use AsAction;


    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(User $owner, array $input): Campaign
    {
//        Gate::forUser($user)->authorize('create', Jetstream::newCampaignModel());
        Validator::make($input, $this->rules($input))->validateWithBag('createCampaign');

        AddingCampaign::dispatch($owner);
        DB::beginTransaction();
        $campaign = null;
        try {
            $campaign = app(Campaign::class)->make($input);
            $campaign->owner()->associate($owner);
            $team = $owner->currentTeam;
            $campaign->team()->associate($team)->save();
            $team->switchCampaign($campaign);
            $owner->switchCampaign($campaign);

            //get variables
            $type = Arr::get($input, 'type');
            $channels = Arr::only($input, Barker::$channels);
            $missives = Arr::get($input, 'missives', []);
            DB::commit();
        }
        catch (\Exception $exception) {
            DB::rollBack();
        } finally {
            $campaign && CampaignAdded::dispatch($owner, $campaign, $type, $channels, $missives);
        }

        return $campaign;
    }

    public function asJob(User $owner, array $input)
    {
        $this->handle($owner, $input);
    }

    protected function rules(array $input): array
    {
        //TODO: add webhook validation
        return array_filter([
            'email' => ['email', Rule::requiredIf(!Arr::exists($input, 'mobile'))],
            'mobile' => [Rule::requiredIf(!Arr::exists($input, 'email'))],
            'type' => Barker::hasTypes()
                ? ['required', 'string', new Type]
                : null,
        ]);
    }
}
