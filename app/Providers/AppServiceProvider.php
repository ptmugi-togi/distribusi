<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config(['app.locale' => 'id']);
	    Carbon::setLocale('id');

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $braco = Auth::user()->cabang;

                $periodeRaw = DB::table('tperiode')
                    ->where('braco', $braco)
                    ->where('status', 'O')
                    ->orderBy('periode', 'DESC')
                    ->value('periode');

                $formattedPeriode = $periodeRaw 
                    ? Carbon::createFromFormat('Ym', $periodeRaw)->format('m - Y') 
                    : '-';

                $view->with('periode', $formattedPeriode);
            }
        });
    }
}
