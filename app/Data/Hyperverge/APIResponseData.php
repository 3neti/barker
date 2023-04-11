<?php

namespace App\Data\Hyperverge;

use Spatie\LaravelData\Data;

class APIResponseData extends Data
{
    public function __construct(
        public string $status,
        public int $statusCode,
        public array $metadata,
        public ResultData $result,
    ) {}
}
