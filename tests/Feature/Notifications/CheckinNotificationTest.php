<?php

use App\Actions\{CreateCampaign, CreateCampaignItems};
use App\Models\{Campaign, Checkin, Contact, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CheckinNotification;
use Database\Seeders\TeamSeeder;

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

it('can send notification to contacts upon checkin', function (Campaign $campaign, $mobile, $handle) {
    Notification::fake();
    $contact = Contact::factory()->create(['mobile' => $mobile, 'handle' => $handle]);
    $checkin = Checkin::makeFromAgent(app(User::class)->system())->setPerson($contact);

    $contact->notify(new CheckinNotification($checkin));

    Notification::assertSentTo($contact, function (CheckinNotification $notification) use ($checkin) {
        return $notification->checkin->is($checkin);
    });
})->with('campaign items');
