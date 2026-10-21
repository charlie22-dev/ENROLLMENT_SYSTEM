<?php

namespace App\Providers;

use App\Models\Pef;
use App\Policies\PefPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Pef::class, PefPolicy::class);

        Gate::define('access-admin',     fn($user) => $user->isAdmin());
        Gate::define('access-adviser',   fn($user) => $user->isAdviser());
        Gate::define('access-admission', fn($user) => $user->isAdmission());
        Gate::define('access-registrar', fn($user) => $user->isRegistrar());
        Gate::define('access-cashier',   fn($user) => $user->isCashier());
    }
}
