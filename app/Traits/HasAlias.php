<?php

namespace App\Traits;

trait HasAlias
{
    public function initializeHasAlias()
    {
        $this->mergeFillable(['alias']);
    }
}
