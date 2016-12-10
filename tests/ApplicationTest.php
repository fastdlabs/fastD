<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */


use FastD\Application;


class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testBootstrap()
    {
        $app = new Application(__DIR__ . '/src');

        $this->assertEquals(__DIR__ . '/src', $app->getAppPath());
        $this->assertEquals('local', $app->getEnvironment());
        $this->assertEquals('Fast-D', $app->getName());
        $this->assertEquals('PRC', $app['time']->getTimeZone()->getName());
        $this->assertTrue($app->isBooted());
        $this->assertTrue($app->isDebug());
    }

    public function testHandleRequest()
    {

    }
}
