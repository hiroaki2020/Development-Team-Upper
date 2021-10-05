<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchTeamInputValidation;
use App\Models\Team;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class SearchTeamController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  
     * @return \Illuminate\Http\Response
     */
    public function show(SearchTeamInputValidation $request)
    {
        $escaped = '%'.addcslashes($request->searchTeamInput, '%_\\').'%';
        $teams = Team::where('name', 'like', $escaped)
                ->orWhere('description', 'like', $escaped)
                ->orWhereHas('owner', function (Builder $query) use ($escaped) {
                    $query->where('name', 'like', $escaped);
                })
                ->orWhereHas('users', function (Builder $query) use ($escaped) {
                    $query->where('name', 'like', $escaped);
                });
        if((bool)$request->wanted === true) {
            $teams = $teams->orWhereHas('requirements', function (Builder $query) use ($escaped) {
                $query->where('requirement', 'like', $escaped);
            });
        }
        $teams = $teams->get()->load('requirements', 'users', 'owner');
        $returnedTeams = new Collection;
        if((bool)$request->wanted === true) {
            $selectedRequirements = collect($request->requirements);
            $selectedOptions = collect($request->options);
            $sorted = new Collection;
            $teamsWanted = $teams->where('wanted', true);
            $teamsWanted->each(function ($team, $key) use ($selectedRequirements, $selectedOptions, $sorted) {
                $teamRequirements = collect($team->requirements->where('required', true)->pluck('requirement')->all());
                $teamOptions = collect($team->requirements->where('required', false)->pluck('requirement')->all());
                if($teamRequirements->intersect($selectedRequirements)->count() === $selectedRequirements->count()
                && $teamOptions->intersect($selectedOptions)->count() === $selectedOptions->count()
                ) {
                    $sorted->push($team);
                }
            });
            $returnedTeams = $returnedTeams->merge($sorted);
        }
        if((bool)$request->notWanted === true) {
            $teamsNotWanted = $teams->where('wanted', false);
            $returnedTeams = $returnedTeams->merge($teamsNotWanted);
        }
        $returnValue = $returnedTeams->isNotEmpty() ? $returnedTeams->toQuery()->with('requirements', 'users', 'owner')->paginate(8)->withQueryString() : $returnedTeams;
        if($returnedTeams->isNotEmpty()) {
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
