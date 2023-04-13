<?php

namespace App\Pipes\Filters\AssociativeArray;

use Illuminate\Support\{Arr, Str};
use Closure;

class UpdateKeysToLowercase
{
    public function handle(array $array, Closure $next)
    {
        $temp = [];

        foreach ($array as $key => $value) {
            $index = Str::lower($key);
            $temp[$index] = $value;
        }

        return $next($temp);
    }
}

