<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::share([
            'hasTeams' => function (Request $request) {
                if ($request->user()) {
                return $request->user()->hasTeams();
                } 
                return false;
            },
            'locale' => function () {
                return app()->getLocale();
            },
            'language' => function () { 
                $json = resource_path('lang/'. app()->getLocale() .'.json');
                if(!file_exists($json)) {
                    return [];
                }
                return json_decode(file_get_contents($json), true);
            },
            'currentSlideIndexFromServer' => function () {
                return session('current_slide_index');
            },
            'showOpenerFromServer' => function () {
                return session('show_get_started_modal_opener');
            },
            'email' => function (Request $request) {
                if (! $request->user()) {
                    return;
                }
                return $request->user()->makeVisible('email')->email;
            }
        ]);
    }
}
