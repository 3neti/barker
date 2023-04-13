<?php

namespace App\Pipes\Filters\Text;

use Illuminate\Support\Str;
use Closure;

class StudlyToTitle
{
    public function handle(string $text, Closure $next)
    {
        return $next(
            Str::of($text)->replace('_', ' ')->title()->value
        );
    }
}
