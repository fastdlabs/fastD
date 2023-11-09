<?php

namespace tests;

use FastD\Application;
use FastD\Runtime;
use FastD\Server\Handler\HttpHandler;
use FastD\Server\Swoole;
use PHPUnit\Framework\TestCase;

class SwooleTest extends TestCase
{
    public function server(): runtime
    {
        return new Swoole(new Application(include __DIR__ . '/app/bootstrap/swoole.php'));
    }

    public function testBoostrap()
    {
        $server = $this->server();
        $server->bootstrap();
        $this->assertNotEmpty( app()->getBootstrap('swoole'));
    }
}
