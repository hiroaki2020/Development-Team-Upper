<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Team;
use Laravel\Jetstream\Jetstream;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Rules\Role;

class JoinRequestInputPost extends FormRequest
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
     * Validate the input.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'team_id' => ['required', 'integer', Rule::unique('join_requests')->where(function ($query) {
                $query->where('user_id', auth()->user()->id);
            }),
            Rule::in(Team::pluck('id'))],
            'role' => Jetstream::hasRoles()
                            ? ['required', 'string', new Role]
                            : null,
            'message' => ['string', 'nullable', 'max:1000']
        ];
    }

    /**
     * Display error messages.
     */
    public function messages() {
        return [
            'team_id.unique' => __('Request has been sent to this team.'),
        ];
    }

}
