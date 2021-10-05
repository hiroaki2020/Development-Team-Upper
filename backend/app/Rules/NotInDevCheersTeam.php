<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotInDevCheersTeam implements Rule
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
        $trimmed = trim(mb_convert_kana($value, "s", "UTF-8"));
        if($trimmed === "DevCheers' Team") {
            return false;
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
        return __('The team name is reserved.');
    }
}
