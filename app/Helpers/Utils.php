<?php

use Illuminate\Support\Str;
use Endroid\QrCode\{QrCode, Writer\PngWriter};
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

if (!function_exists('generateQRCodeURI')) {
    function generateQRCodeURI(string $data = null):? string
    {
        if (null !== $data) {
            $qr = QrCode::create($data);
            $writer = new PngWriter();
            $result = $writer->write($qr);

            return $result->getDataUri();
        }

        return null;
    }
}

if (!function_exists('unparse_url')) {
    function unparse_url( $parsed_url , $ommit = array( ) )
    {
        //From Open Web Analytics owa_lib.php
        $url           = '';
        $p             = array();
        $p['scheme']   = isset( $parsed_url['scheme'] ) ? $parsed_url['scheme'] . '://' : '';
        $p['host']     = isset( $parsed_url['host'] ) ? $parsed_url['host'] : '';
        $p['port']     = isset( $parsed_url['port'] ) ? ':' . $parsed_url['port'] : '';
        $p['user']     = isset( $parsed_url['user'] ) ? $parsed_url['user'] : '';
        $p['pass']     = isset( $parsed_url['pass'] ) ? ':' . $parsed_url['pass']  : '';
        $p['pass']     = ( $p['user'] || $p['pass'] ) ? $p['pass']."@" : '';
        $p['path']     = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '';
        $p['query']    = isset( $parsed_url['query'] ) ? '?' . $parsed_url['query'] : '';
        $p['fragment'] = isset( $parsed_url['fragment'] ) ? '#' . $parsed_url['fragment'] : '';
        if ( $ommit ) {
            foreach ( $ommit as $key ) {
                if ( isset( $p[ $key ] ) ) {
                    $p[ $key ] = '';
                }
            }
        }

        return $p['scheme'].$p['user'].$p['pass'].$p['host'].$p['port'].$p['path'].$p['query'].$p['fragment'];
    }
}

/**
 * URL before:
 * https://example.com/orders/123?order=ABC009&status=shipped
 *
 * 1. remove_query_params(['status'])
 * 2. remove_query_params(['status', 'order'])
 *
 * URL after:
 * 1. https://example.com/orders/123?order=ABC009
 * 2. https://example.com/orders/123
 */
if (!function_exists('remove_query_params')) {
    function remove_query_params(string $url, array $params = []): string
    {
        $parsed = parse_url($url);
        parse_str(Arr::get($parsed, 'query'), $query);
        foreach($params as $param) {
            unset($query[$param]); // loop through the array of parameters we wish to remove and unset the parameter from the query array
        }
        Arr::set($parsed, 'query', http_build_query($query));

        return unparse_url($parsed);
    }
}

/**
 * URL before:
 * https://example.com/orders/123?order=ABC009
 *
 * 1. add_query_params(['status' => 'shipped'])
 * 2. add_query_params(['status' => 'shipped', 'coupon' => 'CCC2019'])
 *
 * URL after:
 * 1. https://example.com/orders/123?order=ABC009&status=shipped
 * 2. https://example.com/orders/123?order=ABC009&status=shipped&coupon=CCC2019
 */
if (!function_exists('add_query_params')) {
    function add_query_params(string $url, array $params = []): string {
        $parsed = parse_url($url);
        parse_str(Arr::get($parsed, 'query'), $query);
        Arr::set($parsed, 'query', http_build_query(array_merge($query, $params)));

        return unparse_url($parsed);
    }
}

if (!function_exists('surname_first_to_first_name_first')) {
    /**
     * @throws Throwable
     */
    function surname_first_to_first_name_first(string $fullName): string {
        throw_if((!strpos($fullName, ',')), new Exception('No commas in fullName'));

        $nameParts = array_reverse(explode(",", $fullName));
        $nameParts = array_map(fn($namePart) => Str::of($namePart)->squish()->title()->value(), $nameParts);

        return implode(' ', $nameParts);
    }
}

