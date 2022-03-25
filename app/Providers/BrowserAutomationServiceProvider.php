<?php

namespace App\Providers;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\ChromeProcess;

class BrowserAutomationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('chrome', function () {
            return (new ChromeProcess())->toProcess();
        });

        $this->app->bind(RemoteWebDriver::class, function () {
            $options = (new ChromeOptions())->addArguments(config('automation.chrome.arguments'));

            return RemoteWebDriver::create(
                env('REMOTE_WEB_DRIVER_URI', 'http://selenium:4444/wd/hub'),
                DesiredCapabilities::chrome()->setCapability(
                    ChromeOptions::CAPABILITY,
                    $options
                )
            );
        });

        $this->app->bind(Browser::class, function () {
            return new Browser(app()->make(RemoteWebDriver::class));
        });
    }
}
