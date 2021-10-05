<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchUserInputValidation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class SearchUserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  
     * @return \Illuminate\Http\Response
     */
    public function show(SearchUserInputValidation $request)
    {
        $selectedSkills = collect($request->skills);
        $filtered = new Collection;

        $escaped = '%'.addcslashes($request->searchUserInput, '%_\\').'%';
        $users = User::where('name', 'like', $escaped)
                ->orWhere('url', 'like', $escaped)
                ->orWhere('description', 'like', $escaped)
                ->orWhereHas('skills', function (Builder $query) use ($escaped) {
                    $query->where('skill', 'like', $escaped);
                })
                ->get()->load('skills');
        $users->each(function ($user, $key) use ($selectedSkills, $filtered) {
            $userSkills = collect($user->skills()->pluck('skill')->all());
            if($userSkills->intersect($selectedSkills)->count() === $selectedSkills->count()) {
                $filtered->push($user);
            }
        });
        $returnValue = $filtered->isNotEmpty() ? $filtered->toQuery()->with('skills')->paginate(8)->withQueryString() : $filtered;
        if($filtered->isNotEmpty()) {
            $lastPage = $returnValue->lastPage();
            Validator::make($request->all(), [
                'page' => [
                    'integer',
                    "between:1,{$lastPage}",
                ],
            ])->validate();
        }
        return $returnValue;
    }
}
