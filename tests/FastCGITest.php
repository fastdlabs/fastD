<?php

namespace tests;

use FastD\Application;
use FastD\Server\CgiServer;
use FastD\Runtime;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class FastCGITest extends TestCase
{
    public function server(): Runtime
    {
        return new CgiServer('testing', new Application(include __DIR__ . '/app/bootstrap/fastcgi.php'));
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
        $server = $this->server();
        $server->bootstrap();
        
        // 测试基本属性
        $this->assertEquals('fastd', container()->getName());
        $this->assertEquals('testing', container()->getRuntime());
        
        // 测试 logger 服务已注册
        $this->assertTrue(container()->has('logger'));
        $this->assertInstanceOf(Logger::class, container()->get('logger'));
        
        // 测试 config 服务已注册
        $this->assertTrue(container()->has('config'));
    }

    public function testHelper()
    {
        $server = $this->server();
        $server->bootstrap();
        
        // 测试 helper 函数
        $this->assertSame(container(), container());
        $this->assertEquals('fastd', container()->getName());
    }

    public function testServicesRegistration()
    {
        $server = $this->server();
        $server->bootstrap();
        
        // 测试默认服务已注册
        $this->assertTrue(container()->has('config'));
        $this->assertTrue(container()->has('logger'));
        $this->assertTrue(container()->has('matcher'));
        $this->assertTrue(container()->has('event'));
    }

    public function testEventDispatcher()
    {
        $server = $this->server();
        $server->bootstrap();
        
        // 测试 event 服务
        $event = container()->get('event');
        $this->assertInstanceOf(\FastD\Event\EventDispatcher::class, $event);
    }
}
