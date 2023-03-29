<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Checkin, User};
use App\Actions\CreateCheckin;

uses(RefreshDatabase::class, WithFaker::class);

test('checkin - created with inputs using CreateCheckin action', function () {
    $latitude = $this->faker->latitude();
    $longitude = $this->faker->longitude();
    $location = new Point($latitude, $longitude);
    $url = $this->faker->url();
    $input = compact( 'url', 'location');
    $agent = User::factory()->create();
    $checkin = app(CreateCheckin::class)->run($agent, $input);
    expect($checkin->agent()->is($agent))->toBeTrue();
    expect($checkin->location->latitude)->toBe($latitude);
    expect($checkin->location->longitude)->toBe($longitude);
    expect($checkin->url)->toBe($url);
    expect($checkin->QRCodeURI)->toBe(generateQRCodeURI($url));
});

test('checkin - created with inputs using CreateCheckin action as job', function () {
    $latitude = $this->faker->latitude();
    $longitude = $this->faker->longitude();
    $location = new Point($latitude, $longitude);
    $url = $this->faker->url();
    $input = compact( 'url', 'location');
    $agent = User::factory()->create();
    CreateCheckin::dispatch($agent, $input);
    $checkin = Checkin::whereBelongsto($agent, 'agent')->first();
    expect($checkin->location->latitude)->toBe($latitude);
    expect($checkin->location->longitude)->toBe($longitude);
    expect($checkin->url)->toBe($url);
    expect($checkin->QRCodeURI)->toBe(generateQRCodeURI($url));
});
