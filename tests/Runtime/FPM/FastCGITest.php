<?php

namespace Runtime\FPM;

use FastD\Application;
use FastD\Runtime\FPM\FastCGI;
use FastD\Runtime\Runtime;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class FastCGITest extends TestCase
{
    public function bootstrap(): Runtime
    {
        return new FastCGI(new Application(__DIR__ . '/../../App'));
    }

    public function testBootstrap()
    {
        $cgi = $this->bootstrap();
        $logFile = app()->getPath() . '/runtime/log/' . date('Ymd') . '/' . app()->getName() . '.log';
        $ok = logger()->error("test");
        $this->assertTrue($ok);
        $this->assertFileExists($logFile);
    }

    public function testHandleException()
    {
        $cgi = $this->bootstrap();

        try {
            throw new \Exception("test exception");
        } catch (\Exception $exception) {
            $cgi->handleException($exception);
        }
    }
}
