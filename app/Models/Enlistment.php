<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Enlistment extends Pivot
{
    protected $table = 'campaign_team';

    protected $fillable = ['type'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function campaigns()
    {
        return $this->hasManyThrough(Campaign::class, Team::class);
    }
}
