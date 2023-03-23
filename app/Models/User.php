<?php

namespace App\Models;

use App\Traits\HasCampaigns;
use Bavix\Wallet\Traits\{CanConfirm, HasWallet, HasWalletFloat, HasWallets};
use Bavix\Wallet\Interfaces\{Confirmable, Wallet, WalletFloat};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Jetstream\HasProfilePhoto;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\HasTeams;
use Illuminate\Support\Arr;

class User extends Authenticatable implements Confirmable, Wallet, WalletFloat
{
    use HasWallet, CanConfirm, HasWallets, HasWalletFloat;
    use TwoFactorAuthenticatable;
    use HasProfilePhoto;
    use HasApiTokens;
    use Notifiable;
    use HasFactory;
    use HasTeams;
//    use HasCampaigns;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

//    public function campaigns(): HasMany
//    {
//        return $this->hasMany(Campaign::class);
//    }

    public function system(): self
    {
        return Jetstream::findUserByEmailOrFail(config('domain.seeds.user.system.email'));
    }

    public function isSystem(): bool
    {
        return ($this->email === config('domain.seeds.user.system.email'));
    }

    #[ArrayShape(['role' => "string"])] static public function adminRoleAttribute(): array
    {
        return Arr::only(config('domain.seeds.user.system'), 'role');
    }

    static public function defaultRole(): string
    {
        return config('domain.defaults.user.role');
    }

    public function campaigns()
    {
        return $this->currentTeam->campaigns();
    }

    /**
     * Get the current campaign of the user's context.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentCampaign()
    {
        if (is_null($this->current_campaign_id) && $this->currentTeam?->current_campaign_id ) {
            $this->switchCampaign($this->currentTeam->currentCampaign);
        }

        return $this->belongsTo(Campaign::class, 'current_campaign_id');
    }

    /**
     * Switch the user's context to the given campaign.
     *
     * @param  mixed  $campaign
     * @return bool
     */
    public function switchCampaign($campaign)
    {
        $this->forceFill([
            'current_campaign_id' => $campaign->id,
        ])->save();

        $this->setRelation('currentCampaign', $campaign);

        return true;
    }
}
