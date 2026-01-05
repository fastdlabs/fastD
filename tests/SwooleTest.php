<?php

namespace tests;

use FastD\Application;
use FastD\Environment\Swoole;
use FastD\Environment;
use PHPUnit\Framework\TestCase;

class SwooleTest extends TestCase
{
    public function server(): Environment
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
