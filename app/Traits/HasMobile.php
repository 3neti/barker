<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
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
        $query->where('mobile', Phone::number($mobile));
    }

    static public function fromMobile($mobile): ?self
    {
        return self::withMobile($mobile)->first();
    }
}
