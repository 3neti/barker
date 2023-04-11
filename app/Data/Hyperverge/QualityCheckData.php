<?php

namespace App\Data\Hyperverge;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Data;

class QualityCheckData extends Data
{
    public function __construct(
        public string|Optional $blur,
        public string|Optional $glare,
        public string|Optional $blackAndWhite,
        public string|Optional $capturedFromScreen,
        public string|Optional $partialId,
        public string|Optional $eyesClosed,
        public string|Optional $maskPresent,
        public string|Optional $multipleFaces
    ) {}

    public static function prepareForPipeline(Collection $properties) : Collection
    {
        $properties->each(function ($value, $property) use ($properties) {
//            $properties->put($property, filter_var($value['value'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
            $val = empty($value['value']) ? null : $value['value'];
            $properties->put($property, $val);
        });

        return $properties;
    }
}
