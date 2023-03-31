<?php

namespace App\Classes;

use JetBrains\PhpStorm\ArrayShape;

class Hyperverge
{
    public static string $appId = '';

    public static string $appKey = '';

    public static string $endpoint = '';

    public static string $workflowId = '';

    public static array $inputs = [];

    public static array $languages = [];

    public static string $defaultLanguage = '';

    public static string $expiry = '';

    #[ArrayShape([
        'appId' => "string",
        'appKey' => "string"
    ])]
    public function headers(): array
    {
        return [
            'appId' => static::$appId,
            'appKey' => static::$appKey,
        ];
    }

    public function endpoint(): string
    {
        return static::$endpoint;
    }

    #[ArrayShape([
        'workflowId' => "string",
        'redirectUrl' => "string",
        'inputs' => "array",
        'languages' => "array",
        'defaultLanguage' => "string",
        'expiry' => "string"
    ])]
    public function body(): array
    {
        return [
            'workflowId' => static::$workflowId,
            'redirectUrl' => route('hyperverge-result'),
            'inputs' => static::$inputs,
            'languages' => static::$languages,
            'defaultLanguage' => static::$defaultLanguage,
            'expiry' => static::$expiry,
        ];
    }
}
