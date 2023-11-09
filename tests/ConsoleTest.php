<?php

namespace tests;

use FastD\Application;
use FastD\Console\Console;
use FastD\Runtime;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
    public function server(): runtime
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
