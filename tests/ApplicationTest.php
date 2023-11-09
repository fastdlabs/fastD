<?php

namespace tests;

use FastD\Application;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testApplicationBootstrap()
    {
        $application = new Application(include __DIR__ . '/app/bootstrap/fastcgi.php');
        $application->bootstrap();
        $this->assertEquals('fastd', $application->get('config')->get('name'));
        $this->assertInstanceOf(Logger::class, $application->get('logger'));
        $this->assertEquals('fastd', $application->getName());
    }
}
