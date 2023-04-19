<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\{HasCampaignItems, HasCampaignMissives};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


class Campaign extends Model
{
    use HasCampaignMissives;
    use HasCampaignItems;
    use HasFactory;

    protected $fillable = ['name', 'personal_team'];

    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * Get the owner of the campaign.
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the campaign's users including its owner.
     *
     * @return Collection
     */
    public function allUsers(): Collection
    {
        return $this->users->merge([$this->owner]);
    }

    /**
     * Get all of the users that belong to the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, Enlistment::class)
            ->withPivot('role')
            ->withTimestamps()
            ->as('enlistment');
    }

    /**
     * Determine if the given user belongs to the team.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function hasUser($user)
    {
        return $this->users->contains($user) || $user->ownsCampaign($this);
    }

    /**
     * Determine if the given email address belongs to a user on the team.
     *
     * @param  string  $email
     * @return bool
     */
    public function hasUserWithEmail(string $email)
    {
        return $this->allUsers()->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Determine if the given user has the given permission on the team.
     *
     * @param  \App\Models\User  $user
     * @param  string  $permission
     * @return bool
     */
    public function userHasPermission($user, $permission)
    {
        return $user->hasCampaignPermission($this, $permission);
    }

    /**
     * Get all of the pending user invitations for the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
//    public function teamInvitations()
//    {
//        return $this->hasMany(Jetstream::teamInvitationModel());
//    }

    /**
     * Remove the given user from the team.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function removeUser($user)
    {
        if ($user->current_campaign_id === $this->id) {
            $user->forceFill([
                'current_campaign_id' => null,
            ])->save();
        }

        $this->users()->detach($user);
    }

    /**
     * Purge all of the campaign's resources.
     *
     * @return void
     */
    public function purge()
    {
        $this->owner()->where('current_campaign_id', $this->id)
            ->update(['current_campaign_id' => null]);

        $this->users()->where('current_campaign_id', $this->id)
            ->update(['current_campaign_id' => null]);

        $this->users()->detach();

        $this->delete();
    }

    /** teams */
    /**
     * Get the team of the campaign.
     *
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get all of the campaign's teams including its own team.
     *
     * @return Collection
     */
    public function allTeams(): Collection
    {
        return $this->teams->merge([$this->team]);
    }

    /**
     * Get all of the teams that belong to the campaign.
     *
     * @return BelongsToMany
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, Enlistment::class)
            ->withPivot('type')
            ->withTimestamps()
            ->as('enlistment');
    }

    /**
     * Determine if the given user belongs to the team.
     *
     * @param  Team $team
     * @return bool
     */
    public function hasTeam(Team $team): bool
    {
        return $this->teams->contains($team) || $team->ownsCampaign($this);
    }

    /**
     * Remove the given team from the campaign.
     *
     * @param  Team $team
     * @return void
     */
    public function removeTeam(Team $team)
    {
        if ($team->current_campaign_id === $this->id) {
            $team->forceFill([
                'current_campaign_id' => null,
            ])->save();
        }

        $this->teams()->detach($team);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }
}
