<?php

namespace App\Actions;

use App\Events\{AddingCheckin, CheckinAdded};
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\{Checkin, User};
use Illuminate\Support\Arr;

class CreateCheckin
{
    use AsAction;

    public function handle(User $agent, array $input): Checkin
    {
        AddingCheckin::dispatch($agent);
        $checkin = tap(app(Checkin::class)->make($input), function ($checkin) use ($agent, $input) {
            $checkin->setAgent($agent)->setCampaign($agent->currentCampaign)->save();
            $agent->switchCheckin($checkin);
            $contact = $checkin->contact()->create(Arr::only($input, ['mobile', 'handle']));
            $checkin->setPerson($contact);
            $checkin->save();
        });
        CheckinAdded::dispatch($checkin);

        return $checkin;
    }

    public function asJob(User $agent, array $input)
    {
        $this->handle($agent, $input);
    }
}
