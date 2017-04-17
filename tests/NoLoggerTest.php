<?php
use FastD\Application;
use FastD\TestCase;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */
class NoLoggerTest extends TestCase
{
    public function createApplication()
    {
        $app = new Application(__DIR__.'/app/no-logger');

        return $app;
    }

    public function testApplicationBootstrap()
    {
        $this->assertEquals('fast-d', $this->app->getName());
    }

    public function testLogger()
    {
        $this->assertEquals('fast-d', logger()->getName());
    }
}
