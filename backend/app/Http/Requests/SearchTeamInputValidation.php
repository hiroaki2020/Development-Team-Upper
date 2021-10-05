<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\NotOnlySpaces;
use App\Rules\KeysIn;
use App\Models\SkillOption;

class SearchTeamInputValidation extends FormRequest
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
        $acceptable_numbers_of_requirements = range($start, $end);
        $acceptable_numbers_of_options = range($start, $end);
        $max_number_of_requirements = SkillOption::all()->count() - count($this->input('options') ?? []);
        return [
            'searchTeamInput' => ['required', 'string', 'between:1,20', new NotOnlySpaces],
            'requirements' => ['nullable', 'array', 'distinct', new KeysIn($acceptable_numbers_of_requirements), "between:0,${max_number_of_requirements}"],
            'requirements.*' => ['nullable', 'string', 'exists:skill_options,skill', Rule::notIn($this->input('options') ?? [])],
            'options' => ['nullable', 'array', 'distinct', new KeysIn($acceptable_numbers_of_options)],
            'options.*' => ['nullable', 'string', 'exists:skill_options,skill'],
            'wanted' => ['required', 'boolean'],
            'notWanted' => ['required', 'boolean'],
            'page' => ['required', 'integer', 'numeric'],
        ];
    }

    /**
     * Display error messages.
     */
    public function messages()
    {
        return [
            'searchTeamInput.required' => __("You must type something into input box."),
            'searchTeamInput.between' => __('Search words must be shorter than 20.'),
            'requirements.*.not_in' => __('You cannot tick both "required" and "optional" for each skill in "Team requirements and options".'),
            'wanted.in' => __('You must check either "wanted" or "not wanted" of "Team member wanted status".'),
        ];
    }

    public function withValidator($validator)
    {
        if($validator->fails()) {
            return;
        }
        $validator->sometimes('wanted', Rule::in([true]), function($input) {
            return $input['notWanted'] === false;
        });
    }
}
