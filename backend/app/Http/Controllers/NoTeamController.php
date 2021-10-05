<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class NoTeamController extends Controller
{
    public function index()
    {
        return Inertia::render('NoTeam');
    }
}
