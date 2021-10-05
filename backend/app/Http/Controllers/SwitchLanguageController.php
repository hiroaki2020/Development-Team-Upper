<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwitchLanguageController extends Controller
{
    public function index(Request $request)
    {
        if($request->lang === 'en' || $request->lang === 'ja') {
            $locale = $request->lang;
        } else {
            $locale = 'en';
        }
        session()->put('locale', $locale);
        return redirect()->back();
    }
}
