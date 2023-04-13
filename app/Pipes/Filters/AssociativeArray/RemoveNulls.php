<?php

namespace App\Pipes\Filters\AssociativeArray;

use Closure;

class RemoveNulls
{
    public function handle(array $array, Closure $next)
    {
        return $next(array_filter($array, static function ($var) {
            return $var !== null;
        }));
    }
}
