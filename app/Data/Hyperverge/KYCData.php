<?php

namespace App\Data\Hyperverge;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class KYCData extends Data
{
    public function __construct(
        public string $status,
        public int $statusCode,
        public array $metadata,
        #[MapInputName('result')]
        public ApplicationData $application
    ) {}
}
