<?php

namespace App\Data\Hyperverge;

use Spatie\LaravelData\Optional;
use App\Enums\HypervergeModule;
use Spatie\LaravelData\Data;

class ModuleData extends Data
{
    public function __construct(
        public HypervergeModule $moduleId,
        public int $attempts,
        public string|Optional $imageUrl,
        public string|Optional $croppedImageUrl,
        public string|Optional $documentSelected,
        public string|Optional $expectedDocumentSide,
        public APIResponseData $apiResponse
    ) {}
}
