<?php

namespace App\Pipes\Filters\AssociativeArray;

use Illuminate\Support\Str;
use Closure;

class UpdateKeysFromSnakeToTitle
{
    public function handle(array $array, Closure $next)
    {
        $temp = [];

        foreach ($array as $key => $value) {
            $index = Str::of($key)
                ->snake()
                ->replace('_', ' ')
                ->title()
                ->value;
            $temp[$index] = $value;
        }

        return $next($temp);
    }
}

