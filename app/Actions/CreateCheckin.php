<?php

namespace App\Actions;

use App\Events\{AddingCheckin, CheckinAdded};
use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\{Checkin, User};

class CreateCheckin
{
    use AsAction;

    public function handle(User $agent, array $input): Checkin
    {
        AddingCheckin::dispatch($agent);
        $checkin = tap(app(Checkin::class)->make($input), function ($checkin) use ($agent) {
            $checkin->setAgent($agent)->setCampaign($agent->currentCampaign)->save();
        });
        CheckinAdded::dispatch($checkin);

        return $checkin;
    }

    public function asJob(User $agent, array $input)
    {
        $this->handle($agent, $input);
    }
}
