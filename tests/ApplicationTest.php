<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */
use FastD\Application;
use FastD\TestCase;
use ServiceProvider\FooServiceProvider;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ApplicationTest extends TestCase
{
    public function createApplication()
    {
        $app = new Application(__DIR__.'/app/default');

        return $app;
    }

    public function testApplicationBootstrap()
    {
        $this->assertEquals('fast-d', $this->app->getName());
        $this->assertTrue($this->app->isBooted());
    }

    public function testServiceProvider()
    {
        $this->app->register(new FooServiceProvider());
        $this->assertEquals('foo', $this->app['foo']->name);
    }

    public function testConfigurationServiceProvider()
    {
        $this->assertEquals('fast-d', $this->app->get('config')->get('name'));
        $this->assertNull(config()->get('foo'));
        $this->assertFalse(config()->has('not_exists_key'));
    }

    public function testLoggerServiceProvider()
    {
        $request = $this->request('GET', '/');
        $response = $this->app->handleRequest($request);
        $this->assertEquals(200, $response->getStatusCode());

        $request = $this->request('GET', '/not/found');
        $response = $this->app->handleRequest($request);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testCacheServiceProvider()
    {
        $this->assertInstanceOf(FilesystemAdapter::class, $this->app->get('cache')->getCache('default'));
    }

    public function testHandleRequest()
    {
        $response = $this->app->handleRequest($this->request('GET', '/'));
        $this->assertEquals(json_encode(['foo' => 'bar'], TestCase::JSON_OPTION), $response->getBody());
    }

    public function testHandleException()
    {
        $response = $this->app->handleException($this->request('GET', '/'), new LogicException('handle exception'));
        $this->equalsStatus($response, 502);
        $this->assertTrue(file_exists(app()->getPath().'/runtime/logs/error.log'));
    }

    public function testHandleResponse()
    {
        $response = json([
            'foo' => 'bar',
        ]);

        $this->app->handleResponse($response);
        $this->expectOutputString((string) $response->getBody());
    }

    public function testApplicationShutdown()
    {
        $this->app->shutdown($this->request('GET', '/'), json([
            'foo' => 'bar',
        ]));
        $this->assertTrue(file_exists(app()->getPath().'/runtime/logs/access.log'));
    }
}
