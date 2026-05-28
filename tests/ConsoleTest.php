<?php

namespace tests;

use FastD\Application;
use FastD\Terminal\Console;
use FastD\Runtime;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
    public function server(): Runtime
    {
        return new Console('testing', new Application(include __DIR__ . '/app/bootstrap/console.php'));
    }

    public function testBootstrap()
    {
        $server = $this->server();
        $server->bootstrap();
        
        // 测试基本属性
        $this->assertEquals('fastd', container()->getName());
        $this->assertEquals('PRC', container()->getTimezone());
        $this->assertEquals('testing', container()->getRuntime());
        
        // 测试根路径
        $rootPath = container()->getRootPath();
        $this->assertStringContainsString('fastD', $rootPath);
        $this->assertTrue(is_dir($rootPath));
    }

    public function testBootstrapIdempotent()
    {
        $server = $this->server();
        
        // 多次调用 bootstrap 应该只执行一次
        $server->bootstrap();
        $runtime1 = container()->getRuntime();
        
        $server->bootstrap();
        $runtime2 = container()->getRuntime();
        
        $this->assertEquals($runtime1, $runtime2);
    }
}
