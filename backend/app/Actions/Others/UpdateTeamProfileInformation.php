<?php

namespace App\Actions\Others;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\SkillOption;
use Illuminate\Support\Facades\Gate;
use App\Rules\NotOnlySpaces;
use App\Rules\NotInDevCheersTeam;
use App\Rules\KeysIn;

class UpdateTeamProfileInformation
{
    /**
     * Validate and update the given team's profile information.
     *
     * @param  mixed  $team
     * @param  array  $input
     * @return void
     */
    public function update($user, $team, array $input)
    {
        Gate::forUser($user)->authorize('update', $team);
        $start = 0;
        $end = SkillOption::all()->count() -1;
        $acceptable_numbers_of_requirements = range($start, $end);
        $acceptable_numbers_of_options = range($start, $end);
        $max_number_of_requirements = SkillOption::all()->count() - count($input['options']);
        Validator::make($input, [
            '_method' => ['required', 'string', 'in:PUT'],
            'name' => ['required', 'string', 'max:255', new NotInDevCheersTeam, new NotOnlySpaces],
            'photo' => ['nullable', 'image', 'max:10240'],
            'description' => ['nullable', 'string', 'max:1000'],
            'wanted' => ['required', 'boolean'],
            'requirements' => ['nullable', 'array', 'distinct', new KeysIn($acceptable_numbers_of_requirements), "between:0,${max_number_of_requirements}"],
            'requirements.*' => ['nullable', 'string', Rule::in(SkillOption::pluck('skill')), Rule::notIn($input['options'])],
            'options' => ['nullable', 'array', 'distinct', new KeysIn($acceptable_numbers_of_options)],
            'options.*' => ['nullable', 'string', Rule::in(SkillOption::pluck('skill'))]
        ], [
            'name.required' => __('You must type something into input box.'),
        ])->validateWithBag('updateTeamProfileInformation');

        $requirements = collect($input['requirements']);
        $options = collect($input['options']);
        if(!$requirements->intersect($options)->isEmpty()) {
            return false;
        }

        if (isset($input['photo'])) {
            $team->updateProfilePhoto($input['photo']);
        }
        
        $team->forceFill([
            'name' => $input['name'],
            'description' => $input['description'],
            'wanted' => $input['wanted'],
        ])->save();

        $team->requirements()->delete();
        collect($input['requirements'])->each(function($item, $key) use ($team) {
            $team->requirements()->create(['requirement' => $item, 'required' => true]);
        });
        
        collect($input['options'])->each(function($item, $key) use ($team) {
            $team->requirements()->create(['requirement' => $item, 'required' => false]);
        });
    }

}
