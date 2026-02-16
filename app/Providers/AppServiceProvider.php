<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Cause;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Gate::define('admin', function (User $user): bool {
            return $user->isAdmin();
        });

        View::composer('layouts.app', function ($view) {
            if (!auth()->check()) {
                return;
            }

            $view->with('sidebarCauses', Cause::orderByDesc('created_at')->get());
        });
    }
}
