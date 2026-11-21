<?php declare(strict_types=1);

namespace DeRidderDenHertog\Laravel;

use DeRidderDenHertog\DeRidderDenHertog;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\AggregateServiceProvider;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final class ServiceProvider extends AggregateServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DeRidderDenHertog::class, $this->createRenH(...));
        $this->app->alias(DeRidderDenHertog::class, 'renh');
    }

    /** @throws InvalidArgumentException */
    private function createRenH(Application $app): DeRidderDenHertog
    {
        $config = $this->config($app['config']->get('services.renh'));

        /** Authentication */
        $renh = DeRidderDenHertog::authenticate($config->guid());

        /** Environment */
        if ($config->wantsTestEnv()) {
            $renh->useTest();
        }

        return $renh;
    }

    /** @throws InvalidArgumentException */
    private function config(mixed $config): Config
    {
        Assert::notNull($config, 'The RenH config is missing.');
        Assert::isArray($config, 'The RenH config must be an array.');
        Assert::keyExists($config, 'api_guid', 'The RenH config is missing the "api_guid" key.');
        Assert::string($config['api_guid'], 'The RenH config is invalid and the "api_guid" key must be a string.');

        $env = $config['env'] ?? 'production';

        Assert::string($env, 'The RenH config is invalid and the "env" key must be a string.');

        return new Config(
            guid: $config['api_guid'],
            env: $env,
        );
    }
}
