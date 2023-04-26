<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CampaignProfile;
use App\Classes\Profile;

trait HasCampaignProfiles
{
    private array $profiles = [];

    public function campaignProfiles(): HasMany
    {
        return $this->hasMany(CampaignProfile::class);
    }

    protected function profiles(): Attribute
    {
        $this->campaignProfiles->each(function($item, $key) {
            tap(new Profile($item->profile, $item->options), function ($profile) use ($key) {
                $this->profiles[$key] = $profile;
            });
        });

        return Attribute::make(
            get: fn ($value) => $this->profiles
        );
    }
}
