<?php

namespace App\Interfaces;

interface Profileable
{
    public function getIdType(): string;
    public function getName(): string;
}
