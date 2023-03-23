<?php

use Illuminate\Support\Arr;

if (!function_exists('extractTeamFromName')) {
    function extractTeamFromName(string &$name, string $defaultTeam = null, string $regex = "/(?<name>.*\b).*\((?<team>.*)\)/"): ?string
    {
        if (preg_match($regex, $name, $matches)) {
            $name = Arr::get($matches, 'name');
            $team = Arr::get($matches, 'team');
        }

        return $team ?? $defaultTeam;
    }
}
