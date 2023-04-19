<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use JetBrains\PhpStorm\ArrayShape;

class CampaignMissive extends Model
{
    use HasFactory;

    protected $fillable = ['missive', 'channel', 'template'];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
