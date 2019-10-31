<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Ninecharacters implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {   
        $length  = strlen($value);

        if (is_numeric($value)) {
            if ($length != 9 ) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Must contain exactly 9 numbers.';
    }
}
