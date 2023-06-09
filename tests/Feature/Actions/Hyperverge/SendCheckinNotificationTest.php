<?php

use App\Actions\{CreateCampaign, CreateCampaignItems, CreateCheckin};
use App\Actions\Hyperverge\SendCheckinNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use App\Notifications\CampaignNotification;
use Database\Seeders\TeamSeeder;
use App\Models\{Campaign, User};
use App\Classes\Barker;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed(TeamSeeder::class);
});


dataset('campaign items', function (
    $name = 'Event Sign-up',
    $type = 'accounting',
    $channels = ['email' => 'lbhurtado@me.com', 'mobile' => '09173011987']
) {
    return [
        [
            fn() => tap(app(CreateCampaign::class)->run(app(User::class)->system(), [
                'name' => $name, 'type' => $type
            ]),
                function ($campaign) use ($type, $channels) {
                    app(CreateCampaignItems::class)->run($campaign, $type, $channels);
                }), //campaign
            '09173011987', //contact|registrant mobile
            'Jane de Joya', //contact|registrant name
        ]

    ];
});

it('can inject barker', function () {
    expect(app(SendCheckinNotification::class)->barker)->toBeInstanceOf(Barker::class);
});

it('can send sms to checkin contact', function (Campaign $campaign, $mobile, $handle) {
    $checkin = tap(app(CreateCheckin::class)->run($campaign->owner, compact('mobile', 'handle')), function ($checkin) {
        app(SendCheckinNotification::class)->run($checkin);
    });
})->with('campaign items');
