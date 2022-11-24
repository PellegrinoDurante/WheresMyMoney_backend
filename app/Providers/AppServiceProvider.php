<?php

namespace App\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        if (App::environment('production')) {
            URL::forceScheme('https');
        }

        $socialite = $this->app->make(Factory::class);

        $socialite->extend('nordigen', function () use ($socialite) {
            $config = config('services.nordigen');
            return $socialite->buildProvider(NordigenProvider::class, $config);
        });
    }
}
