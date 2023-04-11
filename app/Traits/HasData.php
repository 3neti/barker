<?php

namespace App\Traits;

use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

trait HasData
{
    protected array $schemalessAttributes = [
        'data',
    ];

    public function initializeHasData()
    {
        $this->mergeFillable(['data']);
        $this->mergeCasts([
            'data' => SchemalessAttributes::class,
        ]);
    }
}
