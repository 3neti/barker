<?php

namespace App\Actions\Hyperverge;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Events\URLGenerated;
use App\Models\Checkin;

class UpdateCheckinUrl
{
    use AsAction;

    public function handle(string $transactionId, string $url): void
    {
        $checkin = app(Checkin::class)->find($transactionId);
        $checkin->update(compact('url'));
        $checkin->save();
    }

    public function asJob(string $transactionId, string $url)
    {
        $this->handle($transactionId, $url);
    }

    public function asListener(URLGenerated $event)
    {
        $this->handle($event->transactionId, $event->url);
    }
}
