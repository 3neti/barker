<?php

namespace App\Data\Hyperverge;

use Spatie\LaravelData\{Data, Optional};
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class DetailData extends Data
{
    public function __construct(
        public ?string                   $idType,
        public IDData|Optional           $fieldsExtracted,
        public string|Optional           $croppedImageUrl,
        public bool|Optional             $liveFace,
        public QualityCheckData|Optional $qualityChecks,
        public bool|Optional             $match,
    ) {}

    public static function prepareForPipeline(Collection $properties) : Collection
    {
        $properties->each(function ($value, $property) use ($properties) {
            $val = empty($value['value']) ? null : $value['value'];
            if (in_array($property, ['liveFace', 'match'])) {
                $properties->put($property, $val);
            };
        });

        return $properties;
    }

    #[ArrayShape(['by' => "string"])]
    public function with(): array
    {
        return [
            'by' => config('app.name')
        ];
    }
}
