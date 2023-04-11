<?php

namespace App\Data\Hyperverge;

use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Attributes\WithCast;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use DateTime;

class IDData extends Data
{
    public function __construct(
        public ?string $firstName,
        public ?string $middleName,
        public ?string $lastName,
        public ?string $fullName,
//        #[WithCast(DateTimeInterfaceCast::class, format: 'd-m-Y')]
//        public ?DateTime $dateOfBirth,
        public ?string $dateOfBirth,
        public ?string $dateOfIssue,
        public ?string $dateOfExpiry,
        public ?string $countryCode,
        public ?string $type,
        public ?string $address,
        public ?string $gender,
        public ?string $idNumber,
        public ?string $placeOfBirth,
        public ?string $placeOfIssue,
        public ?int $yearOfBirth,
        public ?string $age,
        public ?string $fatherName,
        public ?string $motherName,
        public ?string $husbandName,
        public ?string $spouseName,
        public ?string $nationality,
        public ?string $mrzString,
        public ?string $homeTown,
    ) {}

    public static function prepareForPipeline(Collection $properties) : Collection
    {
        $properties->each(function ($value, $property) use ($properties) {
            $val = empty($value['value']) ? null : $value['value'];
            $properties->put($property, $val);
        });

        return $properties;
    }
}
