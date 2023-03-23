<?php

namespace App\Traits;

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
            ->withPivot('role')
            ->withTimestamps()
            ->as('enlistment')
            ;
    }
//    public function campaigns()
//    {
//        return $this->belongsToMany(Campaign::class, Enlistment::class)
//            ->withPivot('role')
//            ->withTimestamps()
//            ->as('enlistment')
//            ;
//    }

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
     * Get the role that the user has on the campaign.
     *
     * @param  mixed  $campaign
     * @return \Laravel\Jetstream\Role|null
     */
    public function campaignRole($campaign)
    {
        if ($this->ownsCampaign($campaign)) {
            return new OwnerRole;
        }

        if (! $this->belongsToCampaign($campaign)) {
            return;
        }

        $role = $campaign->teams
            ->where('id', $this->id)
            ->first()
            ->enlistment
            ->role;

        return $role ? Jetstream::findRole($role) : null;
    }

    /**
     * Determine if the user has the given role on the given campaign.
     *
     * @param  mixed  $campaign
     * @param  string  $role
     * @return bool
     */
    public function hasCampaignRole($campaign, string $role)
    {
        if ($this->ownsCampaign($campaign)) {
            return true;
        }

        return $this->belongsToCampaign($campaign) && optional(Jetstream::findRole($campaign->teams->where(
                'id', $this->id
            )->first()->enlistment->role))->key === $role;
    }

//    /**
//     * Get the user's permissions for the given campaign.
//     *
//     * @param  mixed  $campaign
//     * @return array
//     */
//    public function campaignPermissions($campaign)
//    {
//        if ($this->ownsCampaign($campaign)) {
//            return ['*'];
//        }
//
//        if (! $this->belongsToCampaign($campaign)) {
//            return [];
//        }
//
//        return (array) optional($this->campaignRole($campaign))->permissions;
//    }
//
//    /**
//     * Determine if the user has the given permission on the given campaign.
//     *
//     * @param  mixed  $campaign
//     * @param  string  $permission
//     * @return bool
//     */
//    public function hasCampaignPermission($campaign, string $permission)
//    {
//        if ($this->ownsCampaign($campaign)) {
//            return true;
//        }
//
//        if (! $this->belongsToCampaign($campaign)) {
//            return false;
//        }
//
//        if (in_array(HasApiTokens::class, class_uses_recursive($this)) &&
//            ! $this->tokenCan($permission) &&
//            $this->currentAccessToken() !== null) {
//            return false;
//        }
//
//        $permissions = $this->campaignPermissions($campaign);
//
//        return in_array($permission, $permissions) ||
//            in_array('*', $permissions) ||
//            (Str::endsWith($permission, ':create') && in_array('*:create', $permissions)) ||
//            (Str::endsWith($permission, ':update') && in_array('*:update', $permissions));
//    }
}
