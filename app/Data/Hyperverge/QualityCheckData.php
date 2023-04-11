<?php

namespace App\Data\Hyperverge;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Data;

class QualityCheckData extends Data
{
    public function __construct(
        public bool|Optional $blur,
        public bool|Optional $glare,
        public bool|Optional $blackAndWhite,
        public bool|Optional $capturedFromScreen,
        public bool|Optional $partialId,
        public bool|Optional $eyesClosed,
        public bool|Optional $maskPresent,
        public bool|Optional $multipleFaces
    ) {}

    public static function prepareForPipeline(Collection $properties) : Collection
    {
        $properties->each(function ($value, $property) use ($properties) {
            $properties->put($property, filter_var($value['value'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
        });

        return $properties;
    }
}
