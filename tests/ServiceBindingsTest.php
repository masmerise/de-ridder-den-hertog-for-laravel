<?php declare(strict_types=1);

namespace DeRidderDenHertog\Laravel\Tests;

use DeRidderDenHertog\Core\Http\DeRidderDenHertogConnector;
use DeRidderDenHertog\DeRidderDenHertog;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use ReflectionProperty;

final class ServiceBindingsTest extends DeRidderDenHertogTestCase
{
    #[Test]
    public function exception_is_thrown_if_config_is_missing(): void
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The RenH config is missing.');

        // Act
        $this->app[DeRidderDenHertog::class];
    }

    #[Test]
    public function exception_is_thrown_if_config_is_incomplete(): void
    {
        // Arrange
        $this->app['config']->set('services.renh', []);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The RenH config is missing the "api_guid" key.');

        // Act
        $this->app[DeRidderDenHertog::class];
    }

    #[Test]
    public function renh_can_be_resolved(): void
    {
        // Arrange
        $this->app['config']->set('services.renh', [
            'api_guid' => '{4844a45c-33d1-4937-83f4-366d36449eaf}',
        ]);

        // Act
        $instance = $this->app[DeRidderDenHertog::class];

        // Assert
        $this->assertInstanceOf(DeRidderDenHertog::class, $instance);
    }

    #[Test]
    public function renh_can_be_resolved_using_alias(): void
    {
        // Arrange
        $this->app['config']->set('services.renh', [
            'api_guid' => '{4844a45c-33d1-4937-83f4-366d36449eaf}',
        ]);

        // Act
        $instance = $this->app['renh'];

        // Assert
        $this->assertInstanceOf(DeRidderDenHertog::class, $instance);
    }

    #[Test]
    public function renh_can_be_resolved_using_helper(): void
    {
        // Arrange
        $this->app['config']->set('services.renh', [
            'api_guid' => '{4844a45c-33d1-4937-83f4-366d36449eaf}',
        ]);

        // Act
        $instance = renh();

        // Assert
        $this->assertInstanceOf(DeRidderDenHertog::class, $instance);
    }

    #[Test]
    public function renh_uses_production_environment_by_default(): void
    {
        // Arrange
        $this->app['config']->set('services.renh', [
            'api_guid' => '{4844a45c-33d1-4937-83f4-366d36449eaf}',
        ]);

        // Act
        $url = $this->baseUrl($this->app[DeRidderDenHertog::class]);

        // Assert
        $this->assertSame('https://renh.online/RHAPI_WEB/awws/RHAPI.awws', $url);
    }

    #[Test]
    public function renh_uses_test_environment_if_configured(): void
    {
        // Arrange
        $this->app['config']->set('services.renh', [
            'api_guid' => '{4844a45c-33d1-4937-83f4-366d36449eaf}',
            'env' => 'test',
        ]);

        // Act
        $url = $this->baseUrl($this->app[DeRidderDenHertog::class]);

        // Assert
        $this->assertSame('https://test.renh.online/rhapi_web/awws/rhapi.awws', $url);
    }

    #[Test]
    public function exception_is_thrown_if_env_is_invalid(): void
    {
        // Arrange
        $this->app['config']->set('services.renh', [
            'api_guid' => '{4844a45c-33d1-4937-83f4-366d36449eaf}',
            'env' => true,
        ]);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The RenH config is invalid and the "env" key must be a string.');

        // Act
        $this->app[DeRidderDenHertog::class];
    }

    private function baseUrl(DeRidderDenHertog $renh): string
    {
        $client = new ReflectionProperty($renh, 'client')->getValue($renh);

        $this->assertInstanceOf(DeRidderDenHertogConnector::class, $client);

        return $client->resolveBaseUrl();
    }
}
