<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use App\Models\SkillOption;
use App\Rules\NotOnlySpacesForName;
use App\Rules\NotInDevCheers;
use App\Rules\KeysIn;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        $start = 0;
        $end = SkillOption::all()->count() -1;
        $acceptable_numbers_of_skills = range($start, $end);
        Validator::make($input, [
            '_method' => ['required', 'string', 'in:PUT'],
            'name' => ['required', 'string', 'max:255', new NotInDevCheers, new NotOnlySpacesForName],
            'photo' => ['nullable', 'image', 'max:10240'],
            'description' => ['nullable', 'string', 'max:1000'],
            'url' => ['nullable', 'url'],
            'skills' => ['nullable', 'array', 'distinct', new KeysIn($acceptable_numbers_of_skills)],
            'skills.*' => ['nullable', 'string', Rule::in(SkillOption::pluck('skill'))]
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }  
        
        $user->forceFill([
            'name' => $input['name'],
            'description' => $input['description'],
            'url' => $input['url'],
        ])->save();
        $user->skills()->delete();
        collect($input['skills'])->each(function($item, $key) use ($user) {
            $user->skills()->create(['skill' => $item]);
        });
    }
}
