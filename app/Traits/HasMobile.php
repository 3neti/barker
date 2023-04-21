<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use libphonenumber\PhoneNumberFormat;
use App\Classes\Phone;

trait HasMobile
{
    public function initializeHasMobile()
    {
        $this->mergeFillable(['mobile']);
        $this->mergeCasts([
            'mobile_verified_at' => 'datetime'
        ]);
    }

    public function routeNotificationForEngageSpark()
    {
        $field = config('engagespark.notifiable.route');

        return $this->{$field};
    }

    public function scopeWithMobile(Builder $query, string $mobile): void
    {
        $query->where('mobile', Phone::number($mobile, PhoneNumberFormat::E164));
    }

    static public function fromMobile($mobile): ?self
    {
        return self::withMobile($mobile)->first();
    }

    protected function mobile(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Phone::number($value, PhoneNumberFormat::INTERNATIONAL) : null,
            set: fn (string $value) => Phone::number($value, PhoneNumberFormat::E164),
        );
    }
}
