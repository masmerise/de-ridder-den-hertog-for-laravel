<?php declare(strict_types=1);

namespace DeRidderDenHertog\Laravel\Tests;

use DeRidderDenHertog\Authentication\Failure\CouldNotAuthenticate;
use DeRidderDenHertog\DeRidderDenHertog;
use PHPUnit\Framework\Attributes\Test;
use Throwable;

final class SmokeTest extends DeRidderDenHertogTestCase
{
    #[Test]
    public function execute(): void
    {
        // Setup
        $this->app['config']->set('services.renh', [
            'api_guid' => '{4844a45c-33d1-4937-83f4-366d36449eaf}',
        ]);

        // Run
        try {
            $this->app[DeRidderDenHertog::class]->getApiFunctions();

            $this->fail('Should have thrown an authentication exception.');
        } catch (Throwable $ex) {
            $this->assertInstanceOf(CouldNotAuthenticate::class, $ex);
        }
    }
}
