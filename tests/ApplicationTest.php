<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

include_once __DIR__ . '/TestCase.php';

class ApplicationTest extends TestCase
{
    public function testApplicationInitialize()
    {
        $app = $this->createApplication();

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
