<?php

namespace App\Pipes\Filters\Text;

use Illuminate\Support\Arr;
use Closure;

class LookupIdType
{
    public function handle(string $text, Closure $next)
    {
        return $next(
            Arr::get(config('domain.dictionary.id_type'), $text, 'Unsupported ID Type')
        );
    }
}
