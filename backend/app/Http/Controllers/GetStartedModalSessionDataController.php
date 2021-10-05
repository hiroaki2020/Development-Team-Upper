<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetStartedModalSessionDataController extends Controller
{
    public function index()
    {
        $showOpener = session('show_get_started_modal_opener') ?? false;
        $currentSlideIndex = session('current_slide_index') ?? 0;
        return [
            'showOpener' => $showOpener,
            'currentSlideIndex' => $currentSlideIndex
        ];
    }
}
