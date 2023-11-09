<?php

namespace tests;

use FastD\Application;
use FastD\Console\Process;
use FastD\Runtime;
use PHPUnit\Framework\TestCase;

class ProcessTest extends TestCase
{
    public function server(): runtime
    {
        return new Process(new Application(include __DIR__ . '/app/bootstrap/process.php'));
    }

    public function testBoostrap()
    {
        $server = $this->server();
        $server->bootstrap();
        $this->assertNotEmpty(app()->getBoostrap('process'));
        $this->assertArrayHasKey('demo', app()->getBoostrap('process'));
    }
}
