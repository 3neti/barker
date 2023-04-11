<?php

namespace App\Data\Hyperverge;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Data;
use Illuminate\Support\Arr;

class ApplicationData extends Data
{
    public function __construct(
        public string $applicationStatus,
        public WorkflowData $workflowDetails,
        /** @var ModuleData[] */
        #[MapInputName('results')]
        public DataCollection $modules
    ) {}

    public static function from(...$payloads): static
    {
        //instead of numeric index, might as well make the moduleId
        $keyed = Arr::keyBy($payloads[0]['results'], 'moduleId');// crux :-)
        $payloads[0]['results'] = $keyed;

        return parent::from(...$payloads);
    }
}
