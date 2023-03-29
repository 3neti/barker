<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Events\CampaignAdded;
use App\Models\Campaign;

class CreateCampaignItems
{
    use AsAction;

    public function handle(Campaign $campaign, string $type, array $channels): int
    {
        $count = 0;
        foreach ($channels as $channel => $value) {
            $subject = trans('barker.checkin.header')[$type][$channel];
            $template = trans('barker.checkin.body')[$type][$channel];
            $uri = match ($channel) {
                'mobile' => __('sms::mobile?subject=:subject&body=:body', ['mobile' => $value, 'subject' => $subject]),
                'email' => __('mailto::email?subject=:subject&body=:body', ['email' => $value, 'subject' => $subject]),
                'url' => add_query_params($value, ['reference' => 'put reference here']),
                default => '',
            };
            $campaign->campaignItems()->create(compact('channel', 'uri', 'template')) && $count++;
        }

        return $count;
    }

    public function asListener(CampaignAdded $event)
    {
        $this->handle($event->campaign, $event->type, $event->channels);
    }
}
