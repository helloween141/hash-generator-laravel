<?php

namespace AvtoDev\HashGeneratorLaravel\Tests;

use AvtoDev\HashGeneratorLaravel\ServiceProvider;
use ReflectionClass;
use ReflectionException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

abstract class AbstractTestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        /** @var Application $app */
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';

        $this->beforeBootstrap($app);

        $app->make(Kernel::class)->bootstrap();

        $this->afterBootstrap($app);

        return $app;
    }

    protected function getStoragePath(): string
    {
        return __DIR__ . '/temp/storage';
    }

    /**
     * @param Application $app
     *
     * @return void
     */
    protected function beforeBootstrap(Application $app): void
    {
        $app->useStoragePath($this->getStoragePath());
    }

    /**
     * @param Application $app
     *
     * @return void
     */
    protected function afterBootstrap(Application $app): void
    {
        $app->register(ServiceProvider::class);
    }
}
