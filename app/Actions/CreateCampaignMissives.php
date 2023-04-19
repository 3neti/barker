<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Events\CampaignAdded;
use App\Models\Campaign;

class CreateCampaignMissives
{
    use AsAction;

    public function handle(Campaign $campaign, array $missives): int
    {
        $count = 0;
        foreach ($missives as $missive => $value) {
//            $subject = trans('barker.missive.header')[$missive];
//            $payload_template = trans('barker.missive.body')[$missive];
            $channel = 'mobile';
            $template = match ($channel) {
                'mobile' => __('barker.missive.content', [
                    'subject' => __('barker.missive.components')['subject'][$missive],
                    'body' => __('barker.missive.components')['body'][$missive],
                    'signature' => __('barker.missive.components')['signature'][$missive],
                ]),
//                'mobile' => __('subject=:subject&body=:body&signature=:signature'),
                default => '',
            };
            $campaign->campaignMissives()
                ->create(
                    compact('missive','channel', 'template')
                ) && $count++;
        }

        return $count;
    }

    public function asListener(CampaignAdded $event)
    {
        $this->handle($event->campaign, $event->missives);
    }
}
