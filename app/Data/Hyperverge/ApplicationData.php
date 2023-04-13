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

    /**
     * normally the results indices are numeric
     * and there are 3 modules in the default application
     * i.e. ID Verification Module, Selfie Verification Module and Face Check Module
     * it is imperative to assign the module id as the index instead of 0,1,2
     * which eliminates the need to iterate
     *
     * all we need now is to create an enum class of the module id options
     * and apply it in choosing the module results
     *
     * @param mixed ...$payloads
     * @return static
     */
    public static function from(...$payloads): static
    {
        $keyed = Arr::keyBy($payloads[0]['results'], 'moduleId');// crux :-)
        $payloads[0]['results'] = $keyed;

        return parent::from(...$payloads);
    }
}
