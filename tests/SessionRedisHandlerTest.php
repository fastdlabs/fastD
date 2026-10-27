<?php

declare(strict_types=1);

namespace tests;

use FastD\Http\Session\SessionGcInterface;
use FastD\Http\Session\SessionHandlerInterface;
use FastD\Session\Handler\SessionRedisHandler;
use PHPUnit\Framework\TestCase;

class SessionRedisHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('ext-redis is required.');
        }
    }

    public function testConstructorDoesNotConnectAndImplementsContracts(): void
    {
        $handler = new SessionRedisHandler([
            'host' => '127.0.0.1',
            'port' => 1,
            'timeout' => 0.01,
        ], 60);

        $this->assertInstanceOf(SessionHandlerInterface::class, $handler);
        $this->assertInstanceOf(SessionGcInterface::class, $handler);
    }

    public function testConnectionFailureIsReported(): void
    {
        $handler = new SessionRedisHandler([
            'host' => '127.0.0.1',
            'port' => 1,
            'timeout' => 0.01,
        ], 60);

        $this->expectException(\RuntimeException::class);

        $handler->read('session');
    }
}
