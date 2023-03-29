<?php

namespace App\Classes;

use App\Classes\Type;

class Barker
{
    public static array $types = [];

    public static array $channels = [];

    public static function type(string $key, string $name, array $channels): Type
    {
        static::$channels = collect(array_merge(static::$channels, $channels))
            ->unique()
            ->sort()
            ->values()
            ->all();

        return tap(new Type($key, $name, $channels), function ($type) use ($key) {
            static::$types[$key] = $type;
        });
    }

    public static function hasTypes()
    {
        return count(static::$types) > 0;
    }
}
