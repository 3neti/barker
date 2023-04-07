<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\{HasData, HasMobile};


class Contact extends Model
{
    use HasFactory;
    use HasMobile;
    use HasData;
    use Notifiable;

    protected $fillable = ['mobile', 'handle'];

    protected $appends = ['name', 'birthdate', 'address', 'reference'];

    public function getRouteKeyName(): string
    {
        return 'checkin_uuid';
    }

    public function checkin(): BelongsTo
    {
        return $this->belongsTo(Checkin::class);
    }

    public function getNameAttribute(): string
    {
        return $this->getAttribute('handle');
    }

    public function getBirthdateAttribute(): string
    {
        return '21 April 9170';
    }

    public function getAddressAttribute(): string
    {
        return 'Quezon City';
    }

    public function getReferenceAttribute(): string
    {
        return route('checkins.show', ['checkin' => $this->getAttribute('checkin_uuid')]);
    }
}
