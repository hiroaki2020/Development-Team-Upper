<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\SkillOption;
use App\Rules\NotOnlySpaces;
use App\Rules\KeysIn;

class SearchUserInputValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $start = 0;
        $end = SkillOption::all()->count() -1;
        $acceptable_numbers_of_skills = range($start, $end);
        return [
            'searchUserInput' => ['required', 'string', 'between:1,20', new NotOnlySpaces],
            'skills' => ['nullable', 'array', 'distinct', new KeysIn($acceptable_numbers_of_skills)],
            'skills.*' => ['nullable', 'string', Rule::in(SkillOption::pluck('skill'))],
            'page' => ['required', 'integer', 'numeric'],
        ];
    }

    /**
     * Display error messages.
     */
    public function messages() {
        return [
            'searchUserInput.required' => __('You must type something into input box.'),
            'searchUserInput.between' => __('Search words must be shorter than 20.'),
        ];
    }
}
