<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;
use App\Models\Checkin;

trait CanCheckin
{
    public function initializeCanCheckin()
    {
//        $this->append(['birthdate', 'address', 'reference']);
//        $this->mergeCasts([
//            'idType' => IDType::class
//        ]);
    }

    public function getRouteKeyName(): string
    {
        return 'checkin_uuid';
    }

    public function checkin(): BelongsTo
    {
        return $this->belongsTo(Checkin::class);
    }

    protected function getCheckinData(): array
    {
        return $this->checkin->getAttribute('data');
    }

    protected function idType(): Attribute
    {
        return Attribute::make(
            get: fn () => Arr::get($this->getCheckinData(), config('domain.hyperverge.mapping.id_type'))
        );
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

    public function getPayloadAttribute(): array
    {
        return Arr::only($this->toArray(), $this->getAppends());
    }
}
