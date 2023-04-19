<?php

namespace App\Classes;

use JsonSerializable;

class Missive implements JsonSerializable
{
    /**
     * The key identifier for the missive.
     *
     * @var string
     */
    public $key;

    /**
     * The text of the missive.
     *
     * @var string
     */
    public $text;

    /**
     * The missive's description.
     *
     * @var string
     */
    public $description;

    /**
     * The missive's timing.
     * e.g. pre-checkin, post-checkin
     *
     * @var string
     */
    public $timing;

    /**
     * Create a new missive instance.
     *
     * @param  string  $key
     * @param  string  $text
     * @return void
     */
    public function __construct(string $key, string $text)
    {
        $this->key = $key;
        $this->text = $text;
    }

    /**
     * Describe the missive.
     *
     * @param  string  $description
     * @return $this
     */
    public function description(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the timing of the missive.
     *
     * @param  string  $timing
     * @return $this
     */
    public function timing(string $timing)
    {
        $this->timing = $timing;

        return $this;
    }

    /**
     * Get the JSON serializable representation of the object.
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'key' => $this->key,
            'text' => __($this->text),
            'description' => __($this->description),
        ];
    }
}
