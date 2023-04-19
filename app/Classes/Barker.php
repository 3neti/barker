<?php

namespace App\Classes;

class Barker
{
    public static array $types = [];

    public static array $channels = [];

    public static array $missives = [
        'instructions' => [],
        'riders' => [],
    ];

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

    public static function hasTypes(): bool
    {
        return count(static::$types) > 0;
    }

    public static function instruction(string $key, string $text): Missive
    {
        return tap(new Missive($key, $text), function ($instruction) use ($key) {
            static::$missives['instructions'][$key] = $instruction;
        });
    }

    public function hasInstructions(): bool
    {
        return count(static::$missives['instructions']) > 0;
    }

    public static function rider(string $key, string $text): Missive
    {
        return tap(new Missive($key, $text), function ($rider) use ($key) {
            static::$missives['riders'][$key] = $rider;
        });
    }

    public function hasRiders(): bool
    {
        return count(static::$missives['riders']) > 0;
    }
}
