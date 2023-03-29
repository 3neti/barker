<?php

use App\Models\{Campaign, CampaignItem, User};
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\TeamSeeder;
use App\Actions\CreateCampaign;
use App\Actions\CreateCampaignItems;

uses(WithFaker::class, RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('migrate:fresh');
    $this->seed(TeamSeeder::class);
});

it('can be persisted using fillable attributes', function ($campaignItemTypeAttribsSet) {
    $i = 0;
    foreach ($campaignItemTypeAttribsSet as $type => $campaignItemAttribsSet) {
        /*** arrange ***/
        $name = __('Campaign :i', ['i' => $i++]);
        $campaign = app(CreateCampaign::class)->run(app(User::class)->system(), compact('name', 'type'));

        /*** act ***/
        foreach ($campaignItemAttribsSet as $campaignItemAttribs) {
            $campaignItem = $campaign->campaignItems()->create($campaignItemAttribs);

            /*** assert ***/
            $this->assertDatabaseHas(CampaignItem::class, [
                'id' => $campaignItem->id,
                'campaign_id' => $campaignItem->campaign_id,
                'channel' => $campaignItem->channel,
                'uri' => $campaignItem->uri,
                'template' => $campaignItem->template,
            ]);
        }
    }
})->with(
    [
        [[
            'accounting' => [
                ['channel' => 'email', 'uri' => 'mailto:lester@hurtado.ph', 'template' => 'Template 1'],
                ['channel' => 'sms', 'uri' => 'sms:09189362340', 'template' => 'Template 2']
            ],
        ]],
    ]
);

it('can be persisted using inertia attributes', function ($campaignItemInertiaAttribsSet) {
    $i = 0;
    foreach ($campaignItemInertiaAttribsSet as $type => $campaignItemInertiaAttribs) {
        /*** arrange ***/
        $name = __('Campaign :i', ['i' => $i++]);
        $campaign = app(CreateCampaign::class)->run(app(User::class)->system(), compact('name', 'type'));

        /*** act ***/
        foreach ($campaignItemInertiaAttribs as $channel => $value) {
            $subject = trans('barker.checkin.header')[$type][$channel];
            $template = trans('barker.checkin.body')[$type][$channel];
            $uri = match ($channel) {
                'mobile' => __('sms::mobile?subject=:subject&body=:body', ['mobile' => $value, 'subject' => $subject]),
                'email' => __('mailto::email?subject=:subject&body=:body', ['email' => $value, 'subject' => $subject]),
                'url' => add_query_params($value, ['reference' => 'put reference here']),
                default => '',
            };
            $campaignItem = $campaign->campaignItems()->create(compact('channel', 'uri', 'template'));

            /*** assert ***/
            $this->assertDatabaseHas(CampaignItem::class, [
                'id' => $campaignItem->id,
                'campaign_id' => $campaignItem->campaign_id,
                'channel' => $campaignItem->channel,
                'uri' => $campaignItem->uri,
                'template' => $campaignItem->template,
            ]);
        }
    }
})->with(
    [
        [[
            'accounting' => [
                'mobile' => '09189362340',
                'email' => 'lester@acme.com',
            ],
            'authentication' => [
                'mobile' => '09175180722',
                'email' => 'apple@acme.com',
                'url' => 'https://webhook.acme.com/?secret=1234WXYZ'
            ],
        ]],
    ]
);

it('can be persisted using create campaign items action', function ($campaignItemInertiaAttribsSet) {
    $i = 0;
    foreach ($campaignItemInertiaAttribsSet as $type => $campaignItemInertiaAttribs) {
        /*** arrange ***/
        $name = __('Campaign :i', ['i' => $i++]);
        $campaign = app(CreateCampaign::class)->run(app(User::class)->system(), compact('name', 'type'));

        /*** act ***/
        expect($campaign->campaignItems)->toHaveCount(0);
        app(CreateCampaignItems::class)->run($campaign, $type, $campaignItemInertiaAttribs);
        $campaign = $campaign->fresh();
        expect($campaign->campaignItems)->toHaveCount(sizeof($campaignItemInertiaAttribs));
        $campaign->campaignItems->each(function ($campaignItem) {
            /*** assert ***/
            $this->assertDatabaseHas(CampaignItem::class, [
                'id' => $campaignItem->id,
                'campaign_id' => $campaignItem->campaign_id,
                'channel' => $campaignItem->channel,
                'uri' => $campaignItem->uri,
                'template' => $campaignItem->template,
            ]);
        });
    }
})->with(
    [
        [[
            'accounting' => [
                'mobile' => '09189362340',
                'email' => 'lester@acme.com',
            ],
            'authentication' => [
                'mobile' => '09175180722',
                'email' => 'apple@acme.com',
                'url' => 'https://webhook.acme.com/?secret=1234WXYZ'
            ],
        ]],
    ]
);

it('can be persisted using create campaign action', function ($campaignItemInertiaAttribsSet) {
    $i = 0;


    foreach ($campaignItemInertiaAttribsSet as $type => $campaignItemInertiaAttribs) {
        /*** arrange ***/
        $name = __('Campaign :i', ['i' => $i++]);
        $input = array_merge(compact('name', 'type'), $campaignItemInertiaAttribs);

        /*** act ***/
        $campaign = app(CreateCampaign::class)->run(app(User::class)->system(), $input);

        $campaign->campaignItems->each(function ($campaignItem) {
            /*** assert ***/
            $this->assertDatabaseHas(CampaignItem::class, [
                'id' => $campaignItem->id,
                'campaign_id' => $campaignItem->campaign_id,
                'channel' => $campaignItem->channel,
                'uri' => $campaignItem->uri,
                'template' => $campaignItem->template,
            ]);
        });
    }
})->with(
    [
        [[
            'accounting' => [
                'mobile' => '09189362340',
                'email' => 'lester@acme.com',
            ],
            'authentication' => [
                'mobile' => '09175180722',
                'email' => 'apple@acme.com',
                'url' => 'https://webhook.acme.com/?secret=1234WXYZ'
            ],
        ]],
    ]
);
