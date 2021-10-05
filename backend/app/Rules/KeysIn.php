<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class KeysIn implements Rule
{
    private $acceptable_keys;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $acceptable_keys_passed)
    {
        $this->acceptable_keys = $acceptable_keys_passed;
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
        foreach($value as $array_key => $array_value) {
            if(!in_array($array_key, $this->acceptable_keys, true)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Invalid data found.');
    }
}
