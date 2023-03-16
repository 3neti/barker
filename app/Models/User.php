<?php

namespace App\Models;

use Bavix\Wallet\Traits\{CanConfirm, HasWallet, HasWalletFloat, HasWallets};
use Bavix\Wallet\Interfaces\{Confirmable, Wallet, WalletFloat};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\HasTeams;

class User extends Authenticatable implements Confirmable, Wallet, WalletFloat
{
    use HasWallet, CanConfirm, HasWallets, HasWalletFloat;
    use TwoFactorAuthenticatable;
    use HasProfilePhoto;
    use HasApiTokens;
    use Notifiable;
    use HasFactory;
    use HasTeams;

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
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function system(): self
    {
        return Jetstream::findUserByEmailOrFail(config('domain.seeds.user.system.email'));
    }
}
