<?php

namespace App\Data\Hyperverge;

use Illuminate\Support\Collection;
use Illuminate\Support\Optional;
use Spatie\LaravelData\Data;

class ConfidenceCheckData extends Data
{
    public function __construct(
        public bool $value,
        public string|Optional $confidence
    ) {}
}
