<?php

namespace tests;

use FastD\Application;
use FastD\Environment\Process;
use FastD\Environment;
use PHPUnit\Framework\TestCase;

class ProcessTest extends TestCase
{
    public function server(): Environment
    {
        return new Process(new Application(include __DIR__ . '/app/bootstrap/process.php'));
    }

    public function testBootstrap()
    {
        $server = $this->server();
        $server->bootstrap();
        $this->assertNotEmpty(app()->getBootstrap('process'));
        $this->assertArrayHasKey('demo', app()->getBootstrap('process'));
    }
}
