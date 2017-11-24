<?php

use FastD\Application;
use Monolog\Handler\StreamHandler;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */
class HelpersTest extends \FastD\TestCase
{
    public function createApplication()
    {
        $app = new Application(__DIR__.'/../app');

        return $app;
    }

    public function testFunctionApp()
    {
        $this->assertEquals('fast-d', app()->getName());
    }

    public function testFunctionVersion()
    {
        $this->assertEquals('v3.2.0', version());
    }

    public function testFunctionRoute()
    {
        $router = route();
        $map = $router->aliasMap;
        $this->assertArrayHasKey('GET', $map);
        $this->assertArrayHasKey('POST', $map);
    }

    public function testFunctionConfig()
    {
        $this->assertEquals('fast-d', config()->get('name'));
        $this->assertArrayHasKey('database', config()->all());
        $this->assertArrayHasKey('consoles', config()->all());
    }

    /**
     * @expectedException \FastD\Container\Exceptions\ServiceNotFoundException
     */
    public function testFunctionRequestInApplicationNotBootstrap()
    {
        request();
    }

    public function testFunctionRequestInApplicationHandleRequest()
    {
        $this->handleRequest($this->request('GET', '/'));
        $request = request();
        $this->assertEquals('/', $request->getUri()->getPath());
        $this->assertEquals('GET', $request->getMethod());
    }

    /**
     * @expectedException \FastD\Container\Exceptions\ServiceNotFoundException
     */
    public function testFunctionResponseInApplicationNotBootstrapped()
    {
        response();
    }

    public function testFunctionResponseInApplicationHandleRequest()
    {
        $response = $this->handleRequest($this->request('GET', '/'));
        $this->assertEquals($response, response());
    }

    public function testFunctionJson()
    {
        $response = json(['foo' => 'bar']);
        $this->assertEquals(
            (string) $response->getContents(),
            (string) (new \FastD\Http\JsonResponse(['foo' => 'bar']))->getContents()
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testFunctionAbort()
    {
        abort('400');
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

    public function testFunctionDatabase()
    {
        $this->assertEquals('mysql', database()->info()['driver']);
        $this->assertNotNull(database());
    }
}
