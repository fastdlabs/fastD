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
use Tests\App\WelcomeBundle\WelcomeBundle;

/**
 * Class BootstrapTest
 *
 * @package FastD\Framework\Tests\KernelTesting
 */
class AppTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var App
     */
    protected $app;

    public function setUp()
    {
        $this->app = new App(include __DIR__ . '/../bootstrap.php');
    }

    public function testAppBootstrap()
    {
        $this->assertTrue($this->app->isBooted());
    }

    public function testKernelEnvironment()
    {
        $this->assertEquals('dev', $this->app->getContainer()->singleton('kernel.config')->get('env'));

        $this->assertEquals('dev', $this->app->getEnvironment());

        $this->assertTrue($this->app->isDebug());
    }

    public function testAppConfiguration()
    {
        $config = $this->app->getContainer()->singleton('kernel.config')->all();

        $this->assertEquals('jan', $config['name']);

        $this->assertEquals('zh_CN.UTF-8', $config['lang']);

        $this->assertEquals($this->app->getContainer()->singleton('kernel.config')->get('lang'), 'zh_CN.UTF-8');
    }

    public function testAppBundles()
    {
        $bundles = $this->app->getBundles();

        $this->assertEquals([
            new WelcomeBundle(),
        ], $bundles);
    }
}