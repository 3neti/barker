<?php

namespace App\Actions\Hyperverge;

use App\Events\{CheckinAdded, Hyperverge\URLGenerated};
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
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
            URLGenerated::dispatch($transactionId, $url = $response->json('result.startKycUrl'));
        }

        return $url;
    }

    public function asJob($transactionId): void
    {
        $this->handle($transactionId);
    }

    public function asListener(CheckinAdded $event)
    {
        self::dispatch($event->checkin->uuid);
    }

    public function asController(Request $request)
    {
        self::dispatch($request->get('transactionId'));
    }
}
