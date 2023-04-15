<?php

namespace App\Data\Hyperverge;

use App\Pipes\Filters\AssociativeArray\{RemoveNulls, UpdateKeysFromSnakeToTitle, UpdateKeysToLowercase};
use App\Pipes\Filters\Text\{LookupIdType, StudlyToTitle};
use Spatie\LaravelData\Attributes\MapInputName;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Pipeline\Pipeline;
use App\Enums\HypervergeModule;
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

    #[ArrayShape(['idType' => "null|string", 'fieldsExtracted' => "array|null", 'idImageUrl' => "null|string", 'selfieImageUrl' => "null|string", 'idChecks' => "array|null", 'selfieChecks' => "array|null", 'by' => "mixed"])]
    public function with(): array
    {
        return [
            'idType' => $this->getIdType(),
            'fieldsExtracted' => $this->getFieldsExtracted(),
            'idImageUrl' => $this->getIdImageUrl(),
            'selfieImageUrl' => $this->getSelfieImageUrl(),
            'idChecks' => $this->getIDChecks(),
            'selfieChecks' => $this->getSelfieChecks(),
            'by' => config('app.name'),
        ];
    }

    protected function getDetails(HypervergeModule $module): DetailData
    {
        return $this->application
            ->modules[$module->value]
            ->apiResponse
            ->result
            ->details;
    }

    public function getIdType(): ?string
    {
        return app(Pipeline::class)
            ->send($this->getDetails(HypervergeModule::ID_VERIFICATION)->idType)
            ->through([
                LookupIdType::class,
                StudlyToTitle::class,
            ])
            ->thenReturn();
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
