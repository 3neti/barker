<?php

namespace App\Interfaces;

interface Profileable
{
    public function getIdType(): string;
    public function getName(): string;
    public function getAddress(): string;
    public function getBirthdate(): string;
}
