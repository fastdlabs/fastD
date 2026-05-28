<?php

namespace tests;

use FastD\Application;
use FastD\Server\SwServer;
use FastD\Runtime;
use PHPUnit\Framework\TestCase;

class SwooleTest extends TestCase
{
    public function server(): Runtime
    {
        return new SwServer('testing', new Application(include __DIR__ . '/app/bootstrap/swoole.php'));
    }

    public function testBoostrap()
    {
        $server = $this->server();
        $server->bootstrap();
        
        // 测试基本属性
        $this->assertEquals('fastd', container()->getName());
        $this->assertEquals('testing', container()->getRuntime());
        $this->assertEquals('PRC', container()->getTimezone());
    }

    public function testBootstrapIdempotent()
    {
        $server = $this->server();
        
        // 多次调用 bootstrap 应该只执行一次
        $server->bootstrap();
        $timezone1 = container()->getTimezone();
        
        $server->bootstrap();
        $timezone2 = container()->getTimezone();
        
        $this->assertEquals($timezone1, $timezone2);
    }

    public function testServicesRegistered()
    {
        $server = $this->server();
        $server->bootstrap();
        
        // 测试核心服务已注册
        $this->assertTrue(container()->has('logger'));
        $this->assertTrue(container()->has('config'));
        $this->assertTrue(container()->has('matcher'));
        $this->assertTrue(container()->has('event'));
    }
}
