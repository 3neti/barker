<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AddingCampaign
{
    use Dispatchable;

    /**
     * The campaign owner.
     *
     * @var mixed
     */
    public $owner;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $owner
     * @return void
     */
    public function __construct($owner)
    {
        $this->owner = $owner;
    }
}
