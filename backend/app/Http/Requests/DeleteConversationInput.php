<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteConversationInput extends FormRequest
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
                Rule::exists('conversations', 'id')->where(function($query) {
                    $query->where('team_id', null);
                }),
                Rule::exists('conversation_user')->where(function($query) {
                    $query->where('user_id', auth()->user()->id);
                }),
            ]
        ];
    }
}
