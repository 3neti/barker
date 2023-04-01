<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasData;

class Contact extends Model
{
    use HasFactory;
    use HasData;

    protected $fillable = ['mobile', 'handle'];

    public function getRouteKeyName()
    {
        return 'checkin_uuid';
    }

    public function checkin(): BelongsTo
    {
        return $this->belongsTo(Checkin::class);
    }

    public function getNameAttribute(): string
    {
        return $this->handle;
    }

    public function getBirthdateAttribute(): string
    {
        return '21 April 9170';
    }

    public function getAddressAttribute(): string
    {
        return 'Quezon City';
    }
}
