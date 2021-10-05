<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CurrentSlideRequest;

class CurrentSlideController extends Controller
{
    public function update(CurrentSlideRequest $request)
    {
        session()->put('current_slide_index', $request->current_slide_index);
    }
}
