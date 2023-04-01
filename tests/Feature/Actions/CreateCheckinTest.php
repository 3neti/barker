<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Checkin, User};
use App\Actions\CreateCheckin;
use App\Actions\CreateCampaign;
use Database\Seeders\TeamSeeder;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed(TeamSeeder::class);
});

test('checkin - created with inputs using CreateCheckin action', function () {
    $latitude = $this->faker->latitude();
    $longitude = $this->faker->longitude();
    $location = new Point($latitude, $longitude);
    $url = $this->faker->url();
    $input = compact( 'url', 'location');
    $agent = app(User::class)->system();
    app(CreateCampaign::class)->run($agent, [
        'name' => 'Campaign 1',
        'type' => 'accounting',
        'mobile' => '09189362340',
        'email' => 'lester@acme.com',
    ]);
    $checkin = app(CreateCheckin::class)->run($agent, $input);
    expect($checkin->agent()->is($agent))->toBeTrue();
    expect($checkin->location->latitude)->toBe($latitude);
    expect($checkin->location->longitude)->toBe($longitude);
    expect($checkin->url)->toBe($url);
    expect($checkin->QRCodeURI)->toBe(generateQRCodeURI($url));
});
