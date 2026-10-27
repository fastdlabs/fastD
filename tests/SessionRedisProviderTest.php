<?php

declare(strict_types=1);

namespace tests;

use FastD\Application;
use FastD\Http\Session\Handler\RedisSessionHandler;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SessionRedisProviderTest extends TestCase
{
    public function testProviderRejectsRedisHandlerWhenHttpVersionIsTooOld(): void
    {
        if (!extension_loaded('redis') || class_exists(RedisSessionHandler::class)) {
            $this->markTestSkipped('This case only applies to fastd/http < 8.2 with ext-redis.');
        }

        $this->expectException(InvalidArgumentException::class);

        $application = new Application(include __DIR__ . '/app/bootstrap/fastcgi-session-redis.php');
        $application->bootstrap('testing');
    }

    public function testProviderFailsWhenRedisIsUnavailable(): void
    {
        if (!extension_loaded('redis') || !class_exists(RedisSessionHandler::class)) {
            $this->markTestSkipped('ext-redis and fastd/http >= 8.2 are required.');
        }

        $this->expectException(InvalidArgumentException::class);

        $application = new Application(include __DIR__ . '/app/bootstrap/fastcgi-session-redis.php');
        $application->bootstrap('testing');
    }
}
