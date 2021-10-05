<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\NotOnlySpaces;

class MessageInputValidation extends FormRequest
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
            'messageForm.sender_id' => ['required', 'integer', 'exists:users,id', Rule::in([auth()->user()->id])],
            'messageForm.message' => ['required', 'string', 'max:1000', new NotOnlySpaces],
            'messageForm.is_image' => ['required', 'boolean'],
            'messageForm.currentChatIdToSubmit' => ['required', 'integer', 'exists:conversations,id'],
        ];
    }

}
