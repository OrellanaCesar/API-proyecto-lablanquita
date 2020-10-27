<?php

namespace App\Rules\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoJfifi implements Rule
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
        return !(substr($value->getClientOriginalName(), -4 ) == 'jfif' );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No se acepta imagenes con formato jfifi.';
    }
}
