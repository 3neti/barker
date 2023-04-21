<?php

use App\Classes\Phone;
use App\Events\CheckinAdded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use libphonenumber\PhoneNumberFormat;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Support\Facades\{Event, Http};
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Checkin, Contact, User};
use Database\Seeders\TeamSeeder;
use App\Actions\CreateCheckin;
use App\Actions\CreateCampaign;
use App\Models\Campaign;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed(TeamSeeder::class);
});

test('checkin - created with inputs using CreateCheckin action', function () {
    Event::fake([CheckinAdded::class]);
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

dataset('campaign', function (
    $name = 'Event Sign-up',
    $type = 'accounting',
    $email = 'john@doe.com',
) {
    return [
        [
            fn() => app(CreateCampaign::class)->run(
                app(User::class)->system(), [
                    'name' => $name,
                    'type' => $type,
                    'email' => $email
                ]
            )
        ]

    ];
});

test('checkin - minimum attributes', function (Campaign $campaign) {
    Event::fake([CheckinAdded::class]);
    $checkin = app(CreateCheckin::class)->run($campaign->owner);
    expect($campaign->checkins)->toHaveCount(1);
    expect($checkin->person)->toBeInstanceOf(Contact::class);
})->with('campaign');

test('checkin - minimum attributes null mobile', function (Campaign $campaign) {
    Event::fake([CheckinAdded::class]);
    $checkin = app(CreateCheckin::class)->run($campaign->owner, ['mobile' => null]);
    expect($campaign->checkins)->toHaveCount(1);
    expect($checkin->person)->toBeInstanceOf(Contact::class);
})->with('campaign');

test('checkin - minimum attributes more than once', function (Campaign $campaign) {
    Event::fake([CheckinAdded::class]);
    app(CreateCheckin::class)->run($campaign->owner);
    app(CreateCheckin::class)->run($campaign->owner);
    expect($campaign->checkins)->toHaveCount(2);
})->with('campaign');

test('checkin - mobile attributes', function (Campaign $campaign) {
    Event::fake([CheckinAdded::class]);
    $mobile = '09171234567';
    $checkin = app(CreateCheckin::class)->run($campaign->owner, compact('mobile'));
    expect($campaign->checkins)->toHaveCount(1);
    expect(Contact::all())->toHaveCount(1);
    expect($checkin->person->mobile)->toBe(Phone::number($mobile, PhoneNumberFormat::INTERNATIONAL));
    expect($checkin->person->handle)->toBe(null);
    expect($checkin->uuid)->toBe($checkin->person->checkin_uuid);
})->with('campaign');

test('checkin - mobile attributes more than once', function (Campaign $campaign) {
    Event::fake([CheckinAdded::class]);
    $mobile = '09171234567';
    $checkin1 = app(CreateCheckin::class)->run($campaign->owner, compact('mobile'));
    $checkin2 = app(CreateCheckin::class)->run($campaign->owner, compact('mobile'));
    expect($campaign->checkins)->toHaveCount(2);
    expect(Contact::all())->toHaveCount(1);
    expect($checkin2->person->checkin_uuid)->toBe($checkin2->uuid);
})->with('campaign');
