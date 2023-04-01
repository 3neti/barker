<?php

namespace App\Events\Hyperverge;

use Illuminate\Foundation\Events\Dispatchable;

class URLGenerated
{
    use Dispatchable;

    public function __construct(public string $transactionId, public string $url){}
}
