<?php

namespace App\Data\Hyperverge;

use App\Pipes\Filters\AssociativeArray\{RemoveNulls, UpdateKeysFromSnakeToTitle, UpdateKeysToLowercase};
use App\Enums\{HypervergeIDCard, HypervergeModule};
use Spatie\LaravelData\Attributes\MapInputName;
use Illuminate\Pipeline\Pipeline;
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

    protected function getDetails(HypervergeModule $module): DetailData
    {
        return $this->application
            ->modules[$module->value]
            ->apiResponse
            ->result
            ->details;
    }

    public function getIdType(): HypervergeIDCard
    {
        return $this->getDetails(HypervergeModule::ID_VERIFICATION)->idType;
    }

    public function getFieldsExtracted(): ?array
    {
        return app(Pipeline::class)
            ->send($this->getDetails(HypervergeModule::ID_VERIFICATION)->fieldsExtracted->toArray())
            ->through([
                RemoveNulls::class,
                UpdateKeysFromSnakeToTitle::class
            ])
            ->thenReturn();
    }

    public function getIdImageUrl(): ?string
    {
        return $this->application
            ->modules[HypervergeModule::ID_VERIFICATION->value]
            ->imageUrl
            ?? null;
    }

    public function getSelfieImageUrl(): ?string
    {
        return $this->application
                ->modules[HypervergeModule::SELFIE_VERIFICATION->value]
                ->imageUrl
            ?? null;
    }

    public function getIDChecks(): ?array
    {
        return app(Pipeline::class)
            ->send($this->getDetails(HypervergeModule::ID_VERIFICATION)->qualityChecks->toArray())
            ->through([
                RemoveNulls::class,
                UpdateKeysFromSnakeToTitle::class,
                UpdateKeysToLowercase::class
            ])
            ->thenReturn();
    }

    public function getSelfieChecks(): ?array
    {
        return app(Pipeline::class)
            ->send($this->getDetails(HypervergeModule::SELFIE_VERIFICATION)->qualityChecks->toArray())
            ->through([
                RemoveNulls::class,
                UpdateKeysFromSnakeToTitle::class,
                UpdateKeysToLowercase::class
            ])
            ->thenReturn();
    }
}
