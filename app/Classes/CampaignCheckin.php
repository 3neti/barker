<?php

namespace App\Classes;

use App\Models\{CampaignItem, Checkin};
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Illuminate\Support\Arr;

class CampaignCheckin
{
    public function __construct(public Checkin $checkin, public CampaignItem $campaignItem){}

    public function person(): Model
    {
        return $this->checkin->person;
    }

    public function scheme(): string
    {
        return $this->campaignItem->scheme;
    }

    public function route(): string
    {
        return $this->campaignItem->route;
    }

    #[Pure]
    public function channel(): string
    {
        //TODO: webhook
        return match ($this->scheme()) {
            'mailto' => 'mail',
            'sms' => 'engage_spark'
        };
    }

    #[Pure] #[ArrayShape(['channel' => "string", 'route' => "string"])]
    public function channeledRoute(): array
    {
        return [
          'channel' => $this->channel(),
          'route' => $this->route()
        ];
    }

    #[ArrayShape(['subject' => "string", 'body' => "string", 'type' => "string"])]
    public function payload(): array
    {
        //hydrate the payload template with merged contact data
        $payload_string_data = __($this->campaignItem->payload_template, $this->getPersonFields());

        $uri_query_string_data = __($this->campaignItem->uri_template, [
            'campaign' => $this->campaignItem->campaign->name,
            'template' => $payload_string_data //template is used instead of payload to make it contextual in creating templates
        ]); //hydrate uri template with campaign name and payload

        parse_str($uri_query_string_data, $payload_array_data);

        return $payload_array_data;
    }

    protected function getPersonFields(): array
    {
        //e.g. ['name', 'address', 'birthdate', 'reference']
        return Arr::only($this->checkin->person->toArray(), $this->checkin->person->getAppends());
    }
}
