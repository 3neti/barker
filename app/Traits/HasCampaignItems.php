<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CampaignItem;

trait HasCampaignItems
{
    public function campaignItems(): HasMany
    {
        return $this->hasMany(CampaignItem::class);
    }
}
