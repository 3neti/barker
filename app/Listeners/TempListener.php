<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BalanceUpdated;

class TempListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BalanceUpdated $event): void
    {
        logger('TempListener - start');
        logger($event->getBalance());
        logger('TempListener - end');
    }
}
