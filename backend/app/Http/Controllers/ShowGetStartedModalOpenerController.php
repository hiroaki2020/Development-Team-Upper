<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShowGetStartedModalOpenerRequest;

class ShowGetStartedModalOpenerController extends Controller
{
    public function update(ShowGetStartedModalOpenerRequest $request)
    {
        session()->put('show_get_started_modal_opener', $request->show_opener);
    }
}
