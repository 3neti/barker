<?php

namespace App\Classes;

use JsonSerializable;

//TODO: create a parent to inherit or a trait (with Type, Missive, Profile etc.) e.g. KeyVal
class Profile implements JsonSerializable
{
    /**
     * The key identifier for the profile.
     *
     * @var string
     */
    public $key;

    /**
     * The choices of the profile.
     *
     * @var array
     */
    public $options;

    /**
     * The profile's description.
     *
     * @var string
     */
    public $description;

    /**
     * Create a new instruction instance.
     *
     * @param  string  $key
     * @param  array  $options
     * @return void
     */
    public function __construct(string $key, array $options)
    {
        $this->key = $key;
        $this->options = $options;
    }

    /**
     * Describe the instruction.
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
     * Get the JSON serializable representation of the object.
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'key' => $this->key,
            'options' => $this->options,//TODO: see if json serialize
            'description' => __($this->description),
        ];
    }
}
