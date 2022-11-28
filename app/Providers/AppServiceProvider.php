<?php

namespace App\Providers;

use App\Policies\LeavePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('update_leave', [LeavePolicy::class, 'update']);
        Gate::define('delete_leave', [LeavePolicy::class, 'delete']);
    }
}
