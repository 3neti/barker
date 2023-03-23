<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(
    Tests\TestCase::class,
    // Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

function getPaynamicsPayload(array $attribs): array
{
    return [
        "pchannel" => "gc",
        "signature" => "2d811a752dfdd1603d2119f9a62e173beedf7e1d02daa295c8f14012b752e32c97dae5428eb70b63b55fd68a2e790816b3c3186d1d860b8df3f905a5dfa37a26",
        "timestamp" => "2023-02-19T01:19:29.000+08:00",
        "request_id" => "FTFP87P44ECM760VTZ",
        "merchant_id" => "00000027011198B17BFB",
        "response_id" => "53074965564452864",
        "customer_info" => [
            "zip" => "NA",
            "city" => "NA",
            "name" => $attribs['name'],
            "email" => $attribs['email'],
            "amount" => "{$attribs['amount']}",
            "mobile" => "639177210752",
            "address" => "8 West Maya Drive, Philam Homes",
            "province" => "NA"
        ],
        "pay_reference" => "039664831",
        "response_code" => "GR001",
        "response_advise" => "Transaction is approved",
        "response_message" => "Transaction Successful",
        "processor_response_id" => "039664831"
    ];
}
