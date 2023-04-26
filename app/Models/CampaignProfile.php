<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CampaignProfile extends Model
{
    use HasFactory;

    protected $fillable = ['profile', 'options'];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /** attributes */

    protected function options(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value)
        );
    }
}
