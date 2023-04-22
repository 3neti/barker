<?php

namespace App\Exceptions;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class NotAutoApproved extends HypervergeException
{
    #[Pure]
    public function __construct(public string $transactionId, public string $status)
    {
        $message = 'Transaction not approved.';
        $code = 0;
        $previous = null;

        parent::__construct($message, $code, $previous);
    }

    #[ArrayShape(['transactionId' => "string", 'status' => "string"])]
    public function context(): array
    {
        return [
            'transactionId' => $this->transactionId,
            'status' => $this->status
        ];
    }
}
