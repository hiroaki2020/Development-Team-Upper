<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowImageValidation extends FormRequest
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
        return [
            'conversation_id' => [
                'required',
                'integer',
                Rule::in(auth()->user()->conversations->pluck('id')),
            ],

            'image_path' => [
                'required',
                'string',
                'exists:messages,image_path',
            ],
        ];
    }
}
