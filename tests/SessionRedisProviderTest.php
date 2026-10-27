<?php

declare(strict_types=1);

namespace tests;

use FastD\Application;
use FastD\Session\Handler\SessionRedisHandler;
use PHPUnit\Framework\TestCase;

class SessionRedisProviderTest extends TestCase
{
    protected function setUp(): void
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('ext-redis is required.');
        }
    }

    public function testProviderRegistersRedisHandlerByClassReference(): void
    {
        $application = new Application(include __DIR__ . '/app/bootstrap/fastcgi-session-redis.php');
        $application->bootstrap('testing');

        $this->assertInstanceOf(SessionRedisHandler::class, container()->get('session.handler'));
    }
}
