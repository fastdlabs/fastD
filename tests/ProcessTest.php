<?php

namespace tests;

use FastD\Application;
use FastD\Runtime;
use FastD\Terminal\Process;
use PHPUnit\Framework\TestCase;

class ProcessTest extends TestCase
{
    public function server(): Runtime
    {
        return new Process('testing', new Application(include __DIR__ . '/app/bootstrap/process.php'));
    }

    public function testBootstrap()
    {
        $server = $this->server();
        $server->bootstrap();
        
        // 测试基本属性
        $this->assertEquals('fastd', container()->getName());
        $this->assertEquals('testing', container()->getRuntime());
        
        // 测试根路径存在
        $rootPath = container()->getRootPath();
        $this->assertTrue(is_dir($rootPath));
    }

    public function testBootstrapIdempotent()
    {
        $server = $this->server();
        
        // 多次调用 bootstrap 应该只执行一次
        $server->bootstrap();
        $name1 = container()->getName();
        
        $server->bootstrap();
        $name2 = container()->getName();
        
        $this->assertEquals($name1, $name2);
    }

    public function testServicesRegistered()
    {
        $server = $this->server();
        $server->bootstrap();
        
        // 测试核心服务已注册
        $this->assertTrue(container()->has('logger'));
        $this->assertTrue(container()->has('config'));
        $this->assertTrue(container()->has('event'));
    }
}
