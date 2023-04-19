<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Checkin;

trait CanCheckin
{
    public function initializeCanCheckin()
    {
        $this->append(['birthdate', 'address', 'reference']);
    }

    public function getRouteKeyName(): string
    {
        return 'checkin_uuid';
    }

    public function checkin(): BelongsTo
    {
        return $this->belongsTo(Checkin::class);
    }

    protected function idType(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->checkin->data?->getIdType()
        );
    }

    protected function birthdate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->checkin->data?->getBirthdate()
        );
    }

    protected function address(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->checkin->data?->getAddress()
        );
    }

    protected function reference(): Attribute
    {
        return Attribute::make(
            get: fn () => route('checkins.show', ['checkin' => $this->getAttribute('checkin_uuid')])
        );
    }
}
