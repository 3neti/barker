<?php

namespace App\Data\Hyperverge;

use App\Pipes\Filters\AssociativeArray\{RemoveNulls, UpdateKeysFromSnakeToTitle, UpdateKeysToLowercase};
use App\Pipes\Filters\Text\{LookupIdType, StudlyToTitle};
use Spatie\LaravelData\Attributes\MapInputName;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Pipeline\Pipeline;
use App\Enums\HypervergeModule;
use App\Interfaces\Profileable;
use Spatie\LaravelData\Data;
use Illuminate\Support\Arr;
use Throwable;

class KYCData extends Data implements Profileable
{
    public static bool $rawIdType = true;
    public static bool $rawFieldsExtracted = true;

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

    public function getFieldsExtracted($raw = null): ?array
    {
        $raw = $raw ?? self::$rawFieldsExtracted;
        $fieldsExtracted = $this->getDetails(HypervergeModule::ID_VERIFICATION)->fieldsExtracted->toArray();

        return $raw ? $fieldsExtracted : app(Pipeline::class)
            ->send($fieldsExtracted)
            ->through([
                RemoveNulls::class,
                UpdateKeysFromSnakeToTitle::class
            ])
            ->thenReturn();
    }

//    public function setRawFieldsExtracted($raw = false)
//    {
//        $this->rawFieldsExtracted = $raw;
//    }
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

    public function getIdType($raw = null): string
    {
        $raw = $raw ?? self::$rawIdType;
        $idType = $this->getDetails(HypervergeModule::ID_VERIFICATION)->idType;

        return $raw ? $idType : app(Pipeline::class)
            ->send($idType)
            ->through([
                LookupIdType::class,
                StudlyToTitle::class,
            ])
            ->thenReturn();
    }

    /**
     * @throws Throwable
     */
    public function getName(): string
    {
        $fullName = Arr::get($this->getFieldsExtracted(), 'fullName');

        return match ($this->getIdType()) {
            'phl_dl' => surname_first_to_first_name_first($fullName),
            default => $fullName,
        };
    }

}
