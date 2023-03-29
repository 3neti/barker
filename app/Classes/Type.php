<?php

namespace App\Classes;

use JsonSerializable;

class Type implements JsonSerializable
{
    /**
     * The key identifier for the role.
     *
     * @var string
     */
    public $key;

    /**
     * The name of the role.
     *
     * @var string
     */
    public $name;

    /**
     * The campaign type's channels.
     *
     * @var array
     */
    public $channels;

    /**
     * The role's description.
     *
     * @var string
     */
    public $description;

    /**
     * Create a new role instance.
     *
     * @param  string  $key
     * @param  string  $name
     * @param  array  $channels
     * @return void
     */
    public function __construct(string $key, string $name, array $channels)
    {
        $this->key = $key;
        $this->name = $name;
        $this->channels = $channels;
    }

    /**
     * Describe the role.
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
            'name' => __($this->name),
            'description' => __($this->description),
            'channels' => $this->channels,
        ];
    }
}
