<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CampaignMissive;

trait HasCampaignMissives
{
    public function campaignMissives(): HasMany
    {
        return $this->hasMany(CampaignMissive::class);
    }
}
