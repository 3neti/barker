<?php

namespace App\Data\Hyperverge;

use App\Enums\HypervergeModule;
use Spatie\LaravelData\Data;

class ModuleData extends Data
{
    public function __construct(
        public HypervergeModule $moduleId,
        public int $attempts,
        public APIResponseData $apiResponse
    ) {}
}
