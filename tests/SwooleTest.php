<?php

namespace tests;

use FastD\Application;
use FastD\Terminal\Environment;
use FastD\Server\SwServer;
use PHPUnit\Framework\TestCase;

class SwooleTest extends TestCase
{
    public function server(): Environment
    {
        return new SwServer(new Application(include __DIR__ . '/app/bootstrap/swoole.php'));
    }

    public function testBoostrap()
    {
        $server = $this->server();
        $server->bootstrap();
        $this->assertNotEmpty( app()->getBootstrap('swoole'));
    }
}
