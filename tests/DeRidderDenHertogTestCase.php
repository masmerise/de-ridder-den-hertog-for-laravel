<?php declare(strict_types=1);

namespace DeRidderDenHertog\Laravel\Tests;

use DeRidderDenHertog\Laravel\ServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class DeRidderDenHertogTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }
}
