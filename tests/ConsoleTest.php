<?php

namespace tests;

use FastD\Application;
use FastD\Terminal\Console;
use FastD\Terminal\Environment;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
    public function server(): Environment
    {
        return new Console(new Application(include __DIR__ . '/app/bootstrap/console.php'));
    }

    public function testBootstrap()
    {
        $server = $this->server();
        $server->bootstrap();
        $this->assertIsArray(app()->getBootstrap('commands'));
        $this->assertEmpty(app()->getBootstrap('commands'));
    }
}
