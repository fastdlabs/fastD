<?php

declare(strict_types=1);

namespace tests;

use FastD\Application;
use FastD\Http\Session\Handler\RedisSessionHandler;
use FastD\Http\Session\SessionGcInterface;
use FastD\Http\Session\SessionHandlerInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SessionRedisProviderTest extends TestCase
{
    public function testProviderFailsWhenRedisIsUnavailable(): void
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('ext-redis is required.');
        }

        $this->ensureRedisHandlerClass();

        $this->expectException(InvalidArgumentException::class);

        $application = new Application(include __DIR__ . '/app/bootstrap/fastcgi-session-redis.php');
        $application->bootstrap('testing');
    }

    private function ensureRedisHandlerClass(): void
    {
        if (class_exists(RedisSessionHandler::class)) {
            return;
        }

        class_alias(TestRedisSessionHandler::class, RedisSessionHandler::class);
    }
}

/**
 * Test-only stand-in for fastd/http < 8.2. It is never used by production code.
 */
final class TestRedisSessionHandler implements SessionHandlerInterface, SessionGcInterface
{
    public function __construct(\Redis $redis, int $ttl = 1440, string $prefix = 'fastd:session:', int $maxDataSize = 8192)
    {
    }

    public function read(string $sessionId): array
    {
        return [];
    }

    public function write(string $sessionId, array $data): bool
    {
        return true;
    }

    public function destroy(string $sessionId): bool
    {
        return true;
    }

    public function exists(string $sessionId): bool
    {
        return false;
    }

    public function gc(int $maxLifetime = 0): int
    {
        return 0;
    }
}
