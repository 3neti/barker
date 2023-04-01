<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Actions\Hyperverge\GenerateURL;
use App\Events\Hyperverge\URLGenerated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Request;
use App\Classes\Hyperverge;

uses(RefreshDatabase::class, WithFaker::class);

it('can inject hyperverge headers endpoint body', function () {

    tap(app(GenerateURL::class)->hyperverge->headers(), function (array $headers) {
        expect($headers['appId'])->toBe(config('domain.hyperverge.api.id'));
        expect($headers['appKey'])->toBe(config('domain.hyperverge.api.key'));
    });
    expect(app(GenerateURL::class)->hyperverge->endpoint())->toBe(config('domain.hyperverge.api.url.kyc'));
    tap(app(GenerateURL::class)->hyperverge->body(), function (array $body) {
        expect($body['workflowId'])->toBe(config('domain.hyperverge.url.workflow'));
        expect($body['redirectUrl'])->toBe(route('hyperverge-result'));
        expect($body['inputs'])->toBe(['app' => config('app.name')]);
        expect($body['languages'])->toBe(['en' => 'English']);
        expect($body['defaultLanguage'])->toBe('en');
    });
});

it('can generate the url', function () {
    /*** arrange ***/
    $transactionId = $this->faker->uuid();
    $action = app(GenerateURL::class);
    $url = $this->faker->url();
    Http::fake([$action->hyperverge->endpoint() => Http::response(mockJsonResponse($url), 200)]);
    Event::fake(URLGenerated::class);

    /*** act ***/
    $result = $action->run($transactionId);

    /*** assert ***/
    Http::assertSent(function (Request $request) {
        return
            $request->hasHeader('appId', app(Hyperverge::class)->headers()['appId'])
            && $request->hasHeader('appKey', app(Hyperverge::class)->headers()['appKey'])
            && $request->url() == app(Hyperverge::class)->endpoint()
            && $request['workflowId'] == app(Hyperverge::class)->body()['workflowId']
            && $request['redirectUrl'] == app(Hyperverge::class)->body()['redirectUrl']
            && $request['inputs'] == app(Hyperverge::class)->body()['inputs']
            && $request['languages'] ==  app(Hyperverge::class)->body()['languages']
            && $request['defaultLanguage'] ==  app(Hyperverge::class)->body()['defaultLanguage']
            && $request['expiry'] == app(Hyperverge::class)->body()['expiry']
            ;
    });
    $this->assertEquals($url, $result);

});


function mockJsonResponse(string $url): string
{
    return <<<EOT
{
    "status": "success",
    "statusCode": 200,
    "metadata": {
        "requestId": "1677292617660-8e117723-1dac-4509-9880-698e2514de17"
    },
    "result": {
        "startKycUrl": "$url"
    }
}
EOT;
}
