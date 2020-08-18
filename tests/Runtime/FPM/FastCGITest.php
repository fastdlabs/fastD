<?php

namespace Runtime\FPM;

use FastD\Application;
use FastD\Runtime\FPM\FastCGI;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class FastCGITest extends TestCase
{
    public function testBootstrap()
    {
        $cgi = new FastCGI(new Application(__DIR__ . '/../../App'));
        $logFile = app()->getPath() . '/runtime/log/' . date('Ymd') . '/' . app()->getName() . '.log';
        $ok = logger()->error("test");
        $this->assertTrue($ok);
        $this->assertFileExists($logFile);
    }
}
