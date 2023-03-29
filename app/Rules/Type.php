<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Classes\Barker;

class Type implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, array_keys(Barker::$types));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute must be a valid campaign type.');
    }
}
