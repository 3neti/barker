<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\{Checkin, User};

class CreateCheckin
{
    use AsAction;

    public function handle(User $agent, array $input): Checkin
    {
        return tap(app(Checkin::class)->make($input), function ($checkin) use ($agent) {
            $checkin->setAgent($agent)->save();
        });
    }

    public function asJob(User $agent, array $input)
    {
        $this->handle($agent, $input);
    }
}
