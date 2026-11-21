<?php declare(strict_types=1);

namespace DeRidderDenHertog\Laravel;

use DeRidderDenHertog\Authentication\ApiGuid;

final readonly class Config
{
    public function __construct(
        public string $guid,
        public string $env,
    ) {}

    public function guid(): ApiGuid
    {
        return ApiGuid::fromString($this->guid);
    }

    public function wantsTestEnv(): bool
    {
        return $this->env === 'test';
    }
}
