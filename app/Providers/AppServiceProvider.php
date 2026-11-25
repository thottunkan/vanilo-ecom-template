<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\PageContent;
use App\Models\Policy;
use App\Models\SiteInfo;
use App\Taxes\IndiaTaxCalculator;
use Illuminate\Support\ServiceProvider;
use Vanilo\Taxes\TaxCalculators;
use View;

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
        $siteInfo = SiteInfo::first();
        View::share('siteInfo', $siteInfo);
        View::share('menus', Menu::where('status', 'active')->orderBy('order')->get());
        View::share('policies', Policy::where('status', 'active')->orderBy('order')->get());


        View::composer('layouts.layout', function ($view) {
            $view->with('siteInfo', SiteInfo::first());
        });
        TaxCalculators::register(
            'india',
            IndiaTaxCalculator::class
        );
    }
}
