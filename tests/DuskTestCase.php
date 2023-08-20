<?php

namespace Tests;

use Illuminate\Support\Collection;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     */
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        // $user_agent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:91.0) Gecko/20100101 Firefox/91.0";

        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {

            // #NOTE: To disable dusk headless in env file: DUSK_HEADLESS_DISABLED=true

            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    public static function runServer()
    {
        $env = 'testing';

        // env testing: APP_URL=http://localhost:8081
        
        $webserver = new Process(array(PHP_BINARY, 'artisan', 'serve', '--port=8081', '--env=' .  $env));
        $webserver->disableOutput();
        $webserver->start();
    }

    public static function setUpBeforeClass(): void
    {
        self::runServer();
    }


    /**
     * Determine whether the Dusk command has disabled headless mode.
     */
    protected function hasHeadlessDisabled()
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
            isset($_ENV['DUSK_HEADLESS_DISABLED']) ||
            env('HEADLESS') === 0;
    }

    /**
     * Determine if the browser window should start maximized.
     */
    protected function shouldStartMaximized(): bool
    {
        return isset($_SERVER['DUSK_START_MAXIMIZED']) ||
               isset($_ENV['DUSK_START_MAXIMIZED']);
    }
}
