<?php declare(strict_types=1);

namespace DeRidderDenHertog\Laravel;

use DeRidderDenHertog\Authentication\ApiGuid;
use DeRidderDenHertog\DeRidderDenHertog;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\AggregateServiceProvider;
use InvalidArgumentException;
use Saloon\Config;
use Saloon\HttpSender\HttpSender;
use Webmozart\Assert\Assert;

final class ServiceProvider extends AggregateServiceProvider
{
    public function boot(): void
    {
        if (! class_exists('Saloon\Laravel\SaloonServiceProvider')) {
            Config::$defaultSender = HttpSender::class;
        }
    }

    public function register(): void
    {
        $this->app->singleton(DeRidderDenHertog::class, $this->createRenH(...));
        $this->app->alias(DeRidderDenHertog::class, 'renh');
    }

    /** @throws InvalidArgumentException */
    private function createRenH(Application $app): DeRidderDenHertog
    {
        $guid = $this->guid($app['config']->get('services.renh'));

        return DeRidderDenHertog::authenticate($guid);
    }

    /** @throws InvalidArgumentException */
    private function guid(mixed $config): ApiGuid
    {
        Assert::notNull($config, 'The RenH config is missing.');
        Assert::isArray($config, 'The RenH config must be an array.');
        Assert::keyExists($config, 'api_guid', 'The RenH config is missing the "api_guid" key.');
        Assert::string($config['api_guid'], 'The RenH config is invalid and the "api_guid" key must be a string.');

        /** @var array{api_guid: string} $config */
        return ApiGuid::fromString($config['api_guid']);
    }
}
