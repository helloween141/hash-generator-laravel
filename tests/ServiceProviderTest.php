<?php

declare(strict_types = 1);

namespace AvtoDev\HashGeneratorLaravel\Tests;

use AvtoDev\HashGeneratorLaravel\OpenSslReversibleHash;
use AvtoDev\HashGeneratorLaravel\ReversibleHashInterface;

/**
 * @group  ServiceProviders
 * @covers \AvtoDev\HashGeneratorLaravel\ServiceProvider
 */
class ServiceProviderTest extends AbstractTestCase
{
    /**
     * Tests service-provider loading.
     *
     * @return void
     */
    public function testServiceProviderLoading(): void
    {
        $this->assertInstanceOf(OpenSslReversibleHash::class, $this->app->make(ReversibleHashInterface::class));
    }
}
