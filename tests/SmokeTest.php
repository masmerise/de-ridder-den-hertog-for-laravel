<?php declare(strict_types=1);

namespace DeRidderDenHertog\Laravel\Tests;

use DeRidderDenHertog\Authentication\Failure\CouldNotAuthenticate;
use DeRidderDenHertog\DeRidderDenHertog;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Throwable;

final class SmokeTest extends DeRidderDenHertogTestCase
{
    #[Test]
    public function execute(): void
    {
        // Setup
        Event::fake([RequestSending::class, ResponseReceived::class]);

        $this->app['config']->set('services.renh', [
            'api_guid' => '{4844a45c-33d1-4937-83f4-366d36449eaf}',
        ]);

        // Run
        try {
            $this->app[DeRidderDenHertog::class]->getApiFunctions();

            $this->fail('Should have thrown an authentication exception.');
        } catch (Throwable $ex) {
            $this->assertInstanceOf(CouldNotAuthenticate::class, $ex);

            Event::assertDispatched(RequestSending::class,
                static fn (RequestSending $event) => str_starts_with($event->request->url(), 'https://renh.online/RHAPI_WEB/awws/RHAPI.awws')
            );

            Event::assertDispatched(ResponseReceived::class,
                static fn (ResponseReceived $event) => $event->response->ok()
            );
        }
    }
}
