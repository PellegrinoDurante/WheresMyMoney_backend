<?php

namespace App\Providers;

use App\Exceptions\Solutions\MissingGoogleClientSecretSolutionProvider;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Spatie\Ignition\Contracts\SolutionProviderRepository;

class IgnitionSolutionServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->app->make(SolutionProviderRepository::class)->registerSolutionProvider(MissingGoogleClientSecretSolutionProvider::class);
    }
}
