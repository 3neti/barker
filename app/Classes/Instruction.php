<?php

namespace App\Classes;

use JsonSerializable;

//deprecate
class Instruction implements JsonSerializable
{
    /**
     * The key identifier for the instruction.
     *
     * @var string
     */
    public $key;

    /**
     * The text of the instruction.
     *
     * @var string
     */
    public $text;

    /**
     * The instruction's description.
     *
     * @var string
     */
    public $description;

    /**
     * Create a new instruction instance.
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
            'text' => __($this->text),
            'description' => __($this->description),
        ];
    }
}
