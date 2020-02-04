<?php

namespace App\Providers;

use App\Helpers\CounterHelper;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CounterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '/Helpers/CounterHelper.php';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
