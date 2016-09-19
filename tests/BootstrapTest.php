<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/7/18
 * Time: 上午1:06
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Tests;

use FastD\App;

/**
 * Class BootstrapTest
 *
 * @package FastD\Framework\Tests\KernelTesting
 */
class BootstrapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var App
     */
    protected $app;

    public function setUp()
    {
        $this->app = new App(include __DIR__ . '/../bootstrap.php');
    }

    public function testKernelConfig()
    {
        $this->assertEquals('dev', $this->app->getContainer()->singleton('kernel.config')->get('env'));

        $this->assertEquals([
            /**
             * App 运行环境
             */
            'env' => 'dev',

            /**
             * 源码目录, 程序运行后, 会根据 root.path 加载对应的文件
             */
            'root.path' => __DIR__ . '/App',

            /**
             * 入口目录
             */
            'web.path' => realpath(__DIR__ . '/../web'),

            /**
             * 项目公共配置
             */
            'config' => [

            ],

            /**
             * Swoole Server 配置信息。
             */
            'server' => [],
        ], $this->app->getContainer()->singleton('kernel.config')->all());
    }

    public function testKernelEnv()
    {
        $this->assertEquals('dev', $this->app->getEnvironment());

        $this->assertTrue($this->app->isDebug());
    }
}