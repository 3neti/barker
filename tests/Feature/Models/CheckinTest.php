<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Checkin, User};
use App\Actions\CreateCheckin;

uses(RefreshDatabase::class, WithFaker::class);

test('checkin - created with agent has uuid (not id)', function () {
    $name = $this->faker->name();
    $checkin = tap(app(Checkin::class)->make(), function ($checkin) use ($name) {
        $checkin->setAgent(User::factory()->create(compact('name')))->save();
    });
    expect($checkin->uuid)->toBeString();
    expect($checkin->id)->toBeNull();
    expect($checkin->agent->name)->toBe($name);
});

test('checkin - updated with morphed person', function () {
    $person = User::factory()->create();
    $checkin = Checkin::factory()->create();
    $checkin->person()->associate($person)->save();
    expect($checkin->person()->is($person))->toBeTrue();
});

test('checkin - updated with coordinates', function () {
    $longitude = $this->faker->longitude();
    $latitude = $this->faker->latitude();
    $location = new Point($latitude, $longitude);
    $checkin = Checkin::factory()->create();
    $checkin->update(compact('location'));
    $checkin->save();
    expect($checkin->location->latitude)->toBe($latitude);
    expect($checkin->location->longitude)->toBe($longitude);
});

test('checkin - updated with url (with computed QRCodeURI)', function () {
    $url = $this->faker->url();
    $checkin = Checkin::factory()->create();
    expect($checkin->QRCodeURI)->toBeNull();
    $checkin->update(compact('url'));
    $checkin->save();
    expect($checkin->url)->toBe($url);
    expect($checkin->QRCodeURI)->toBe(generateQRCodeURI($url));
});

test('checkin - updated with data', function () {
    $data = $this->faker->rgbColorAsArray();
    $checkin = Checkin::factory()->create();
    $checkin->update(compact('data'));
    $checkin->save();
    expect($checkin->data)->toBe($data);
    expect($checkin->data)->toBeArray();
});

test('checkin - created with inputs', function () {
    $latitude = $this->faker->latitude();
    $longitude = $this->faker->longitude();
    $location = new Point($latitude, $longitude);
    $url = $this->faker->url();
    $input = compact( 'url', 'location');
    $checkin = tap(app(Checkin::class)->make($input), function ($checkin) {
        $checkin->setAgent(User::factory()->create())->save();
    });
    expect($checkin->location->latitude)->toBe($latitude);
    expect($checkin->location->longitude)->toBe($longitude);
    expect($checkin->url)->toBe($url);
    expect($checkin->QRCodeURI)->toBe(generateQRCodeURI($url));
});

test('checkin - updated eloquently', function () {
    $agent = User::factory()->create();
    $person = User::factory()->create();
    $latitude = $this->faker->latitude();
    $longitude = $this->faker->longitude();
    $url = $this->faker->url();
    $checkin = app(Checkin::class)->make()
        ->setAgent($agent)
        ->setPerson($person)
        ->setLocation($latitude, $longitude)
        ->setURL($url)
    ;
    $checkin->save();
    expect($checkin->agent->is($agent))->toBeTrue();
    expect($checkin->person->is($person))->toBeTrue();
    expect($checkin->location->latitude)->toBe($latitude);
    expect($checkin->location->longitude)->toBe($longitude);
    expect($checkin->url)->toBe($url);
    expect($checkin->QRCodeURI)->toBe(generateQRCodeURI($url));
});
