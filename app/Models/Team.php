<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Jetstream\Team as JetstreamTeam;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\{HasAlias, HasCampaigns};
use App\Models\Scopes\Switchable;
//use Laravel\Jetstream\Jetstream;

class Team extends JetstreamTeam
{
    use HasCampaigns;
    use HasFactory;
    use HasAlias;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'personal_team' => 'boolean',
        'switchable' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
        'switchable',
        'current_campaign_id'
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

//    protected static function boot()
//    {
//        parent::boot();
//
//        static::addGlobalScope(new Switchable);
//    }

    public function default(): ?self
    {
        $record = config('domain.defaults.user.team');

        return app(static::class)->withoutGlobalScope(Switchable::class)->where($record)->first();
    }

//    public function teams(): HasMany
//    {
//        return $this->hasMany(Team::class);
//    }

    public function scopeSwitchable(Builder $builder)
    {
        $builder->where('switchable', '=', 1);
    }
}
