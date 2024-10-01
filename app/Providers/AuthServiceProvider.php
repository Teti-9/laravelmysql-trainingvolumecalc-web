<?php

namespace App\Providers;

use App\Models\Volume;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('create', function ($user, Volume $volume) {
            return $user->id == $volume->user_id;
        });

        Gate::define('update', function ($user, Volume $volume) {
            return $user->id == $volume->user_id;
        });

        Gate::define('delete', function ($user, Volume $volume) {
            return $user->id == $volume->user_id;
        });

        Gate::define('onevolume', function ($user, Volume $volume) {
            return $user->id == $volume->user_id;
        });

        Gate::define('allvolume', function ($user, Volume $volume) {
            return $user->id == $volume->user_id;
        });
    }
}
