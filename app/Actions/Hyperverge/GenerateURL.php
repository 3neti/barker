<?php

namespace App\Actions\Hyperverge;

use App\Events\{CheckinAdded, Hyperverge\URLGenerated};
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Http;
use App\Classes\Hyperverge;

class GenerateURL
{
    use AsAction;

    public function __construct(public Hyperverge $hyperverge){}

    public function handle($transactionId): ?string
    {
        $body = array_merge(compact('transactionId'), $this->hyperverge->body());
        $response = Http::withHeaders($this->hyperverge->headers())->post($this->hyperverge->endpoint(), $body);
        $url = null;
        if ($response->successful()) {
            URLGenerated::dispatch($transactionId, $url = $this->getURL($response));
        }

        return $url;
    }

    public function asJob($transactionId): void
    {
        $this->handle($transactionId);
    }

    public function asListener(CheckinAdded $event)
    {
        $this->handle($event->checkin->uuid);
    }

    protected function getURL($response): string
    {
        return $response->json('result.startKycUrl');
//        $url = $response->json('result.startKycUrl');
//
//        return app('bitly')->getUrl($url);
    }
}
