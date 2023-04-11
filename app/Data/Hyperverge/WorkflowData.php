<?php

namespace App\Data\Hyperverge;

use Spatie\LaravelData\Data;

class WorkflowData extends Data
{
    public function __construct(
        public string $workflowId,
        public int $version,
    ) {}
}
