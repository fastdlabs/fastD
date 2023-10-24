<?php

namespace Runtime\FPM;

use FastD\Application;
use FastD\Http\Response;
use FastD\Runtime\FPM\FastCGI;
use FastD\Runtime\Runtime;
use PHPUnit\Framework\TestCase;

class FastCGITest extends TestCase
{
    public function bootstrap(): Runtime
    {
        return new FastCGI(new Application(__DIR__ . '/../../App'));
    }

    public function dataServerFromGlobals()
    {
        return [
            'PHP_SELF' => '/blog/article.php',
            'GATEWAY_INTERFACE' => 'CGI/1.1',
            'SERVER_ADDR' => 'Server IP: 217.112.82.20',
            'SERVER_NAME' => 'www.blakesimpson.co.uk',
            'SERVER_SOFTWARE' => 'Apache/2.2.15 (Win32) JRun/4.0 PHP/5.2.13',
            'SERVER_PROTOCOL' => 'HTTP/1.0',
            'REQUEST_METHOD' => 'GET',
            'REQUEST_TIME' => 'Request start time: 1280149029',
            'QUERY_STRING' => 'id=10&user=foo',
            'DOCUMENT_ROOT' => '/path/to/your/server/root/',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'HTTP_ACCEPT_ENCODING' => 'gzip,deflate',
            'HTTP_ACCEPT_LANGUAGE' => 'en-gb,en;q=0.5',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_HOST' => 'www.blakesimpson.co.uk',
            'HTTP_REFERER' => 'http://previous.url.com',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6 ( .NET CLR 3.5.30729)',
            'HTTPS' => '1',
            'REMOTE_ADDR' => '193.60.168.69',
            'REMOTE_HOST' => 'Client server\'s host name',
            'REMOTE_PORT' => '5390',
            'SCRIPT_FILENAME' => '/path/to/this/script.php',
            'SERVER_ADMIN' => 'webmaster@blakesimpson.co.uk',
            'SERVER_PORT' => '80',
            'SERVER_SIGNATURE' => 'Version signature: 5.123',
            'SCRIPT_NAME' => '/',
            'REQUEST_URI' => '/',
        ];
    }

    public function testBootstrap()
    {
        $this->bootstrap();
        $logFile = app()->getPath() . '/runtime/log/' . date('Ymd') . '/' . app()->getName() . '.log';
        $ok = logger()->error("test");
        $this->assertTrue($ok);
        $this->assertFileExists($logFile);
    }
/*
    public function testHandleException()
    {
        $cgi = $this->bootstrap();

        try {
            throw new \Exception("test exception");
        } catch (\Exception $exception) {
            $cgi->handleException($exception);
        }
    }

    public function testHandleRoute()
    {
        $cgi = $this->bootstrap();

        $_SERVER = $this->dataServerFromGlobals();
        require_once __DIR__ . '/../../App/config/routes.php';
        $input = $cgi->handleInput();
        $output = container()->get('dispatcher')->dispatch($input);
        $cgi->handleOutput($output);
    }*/
}
