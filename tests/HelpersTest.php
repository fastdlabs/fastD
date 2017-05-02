<?php

use FastD\Application;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */
class HelpersTest extends \FastD\TestCase
{
    public function createApplication()
    {
        $app = new Application(__DIR__.'/app/no-logger');

        return $app;
    }

    public function testFunctionApp()
    {
        $this->assertEquals('fast-d', app()->getName());
    }

    public function testFunctionLogger()
    {
        $logFile = app()->getPath().'/runtime/logs/demo.log';
        logger()->pushHandler(new StreamHandler($logFile));
        logger()->notice('hello world');
        $this->assertTrue(file_exists($logFile));
        unset($logFile);
    }

    public function testFunctionCache()
    {
        $item = cache()->getItem('hello');
        $item->set('world');
        cache()->save($item);
        $this->assertTrue(cache()->getItem('hello')->isHit());
    }

    public function testFunctionConfig()
    {
        $this->assertEquals('fast-d', config()->get('name'));
        $this->assertArrayHasKey('database', config()->all());
    }

    public function testFunctionRequest()
    {
        //        $this->assertNull(request());
    }

    public function testFunctionDatabase()
    {
        $this->assertEquals('mysql', database()->info()['driver']);
        $this->assertNotNull(database());
    }
}
