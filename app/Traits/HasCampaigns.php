<?php

namespace App\Traits;

use App\Classes\Type;
use Laravel\Jetstream\{Jetstream, OwnerRole};
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Models\Enlistment;
use App\Models\Campaign;

trait HasCampaigns
{
    /**
     * Determine if the given campaign is the current campaign.
     *
     * @param  mixed  $campaign
     * @return bool
     */
    public function isCurrentCampaign($campaign)
    {
        return $campaign->id === $this->currentCampaign->id;
    }

    /**
     * @TODO DEPRECATE
     * Get the current campaign of the user's context.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentCampaign()
    {
        if (is_null($this->current_campaign_id) && $this->id) {
            $this->switchCampaign($this->personalCampaign());
        }

        return $this->belongsTo(Campaign::class, 'current_campaign_id');
    }

    /**
     * @TODO DEPRECATE
     * Switch the user's context to the given campaign.
     *
     * @param  mixed  $campaign
     * @return bool
     */
    public function switchCampaign($campaign)
    {
        if (! $this->belongsToCampaign($campaign)) {
            return false;
        }

        $this->forceFill([
            'current_campaign_id' => $campaign->id,
        ])->save();

        $this->setRelation('currentCampaign', $campaign);

        return true;
    }

    /**
     * Get all of the campaigns the user owns or belongs to.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allCampaigns()
    {
        return $this->ownedCampaigns->merge($this->campaigns)->sortBy('name');
    }

    /**
     * Get all of the campaigns the user owns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedCampaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * Get all of the campaigns the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, Enlistment::class)
            ->withPivot('type')
            ->withTimestamps()
            ->as('enlistment')
            ;
    }

    /**
     * Get the user's "personal" campaign.
     *
     * @return \App\Models\Campaign
     */
    public function personalCampaign()
    {
        return $this->ownedCampaigns->where('personal_campaign', true)->first();
    }

    /**
     * Determine if the user owns the given campaign.
     *
     * @param  mixed  $campaign
     * @return bool
     */
    public function ownsCampaign($campaign)
    {
        if (is_null($campaign)) {
            return false;
        }

        return $this->id == $campaign->{$this->getForeignKey()};
    }

    /**
     * Determine if the user belongs to the given campaign.
     *
     * @param  mixed  $campaign
     * @return bool
     */
    public function belongsToCampaign($campaign)
    {
        if (is_null($campaign)) {
            return false;
        }

        return $this->ownsCampaign($campaign) || $this->campaigns->contains(function ($t) use ($campaign) {
                return $t->id === $campaign->id;
            });
    }

    /**
     * Get the type that the user has on the campaign.
     *
     * @param  mixed  $campaign
     * @return \App\Classes\Type|null
     */
    public function campaignType($campaign)
    {
        $type = $campaign->teams
            ->where('id', $this->id)
            ->first()
            ->enlistment
            ->type;

        return $type ? new Type($type, 'asdsadsa', []) : null;
    }

    /**
     * Determine if the user has the given type on the given campaign.
     *
     * @param  mixed  $campaign
     * @param  string  type
     * @return bool
     */
    public function hasCampaignType($campaign, string $type)
    {
        return $this->belongsToCampaign($campaign) && $campaign->teams
                ->where('id', $this->id)
                ->first()
                ->enlistment
                ->type === $type;
    }
}
