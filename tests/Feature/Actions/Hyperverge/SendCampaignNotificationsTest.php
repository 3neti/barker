<?php

use App\Actions\{CreateCampaign, CreateCampaignItems, CreateCheckin};
use App\Actions\Hyperverge\SendCampaignNotifications;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use App\Notifications\CampaignNotification;
use Database\Seeders\TeamSeeder;
use App\Models\{Campaign, User};

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
    expect(app(SendCampaignNotifications::class)->barker)->toBeInstanceOf(\App\Classes\Barker::class);
});

it('can send sms and email notifications to campaign operator', function (Campaign $campaign, $mobile, $handle) {
    Notification::fake();

     $checkin = tap(app(CreateCheckin::class)->run($campaign->owner, compact('mobile', 'handle')), function ($checkin) {
         app(SendCampaignNotifications::class)->run($checkin);
     });

    Notification::assertSentTo(new AnonymousNotifiable,  function (CampaignNotification $notification) use ($checkin, $handle) {
        return array_diff($notification->campaignCheckin->payload(), [
            'subject' => "Event Sign-up - accounted via mobile",
            'body' => "name: {$handle},\nbirthdate: 21 April 9170,\naddress: Quezon City,\nreference: http://localhost/contacts/{$checkin->uuid}",
            'type' => 'accounting'
        ]) == [];
    });
    /** test here */
})->with('campaign items');

//it('can send sms notification to contact', function (Campaign $campaign, $mobile, $handle) {
//    $checkin = app(CreateCheckin::class)->run($campaign->owner, compact('mobile', 'handle'));
//    $listener = app(SendCampaignNotifications::class)->run($checkin);
//
//})->with('campaign items');
