<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */
use FastD\Application;
use FastD\TestCase;
use ServiceProvider\FooServiceProvider;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ApplicationTest extends TestCase
{
    public function createApplication()
    {
        $app = new Application(__DIR__.'/../app');

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

    public function testServiceProviderAutomateConsole()
    {
        $this->app->register(new FooServiceProvider());
        $consoles = config()->get('consoles');
        $consoles = array_unique($consoles);
        $this->assertEquals([
            'Console\Demo', 'ServiceProvider\DemoConsole',
        ], $consoles);
    }

    public function testConfigurationServiceProvider()
    {
        $this->assertEquals('fast-d', $this->app->get('config')->get('name'));
        $this->assertNull(config()->get('foo'));
        $this->assertFalse(config()->has('not_exists_key'));
        $this->assertEquals(config()->get('foo', 'default'), 'default');
        $this->assertEquals(config()->get('env.foo'), 'bar');
    }

    public function testLoggerServiceProvider()
    {
        $logFile = app()->getPath().'/runtime/logs/info.log';

        $request = $this->request('GET', '/');
        $response = $this->app->handleRequest($request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertFileExists($logFile);

        $request = $this->request('GET', '/not/found');
        $response = $this->app->handleRequest($request);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertFileExists($logFile);
    }

    public function testCacheServiceProvider()
    {
        $this->assertInstanceOf(FilesystemAdapter::class, $this->app->get('cache')->getCache('default'));
        $foo = cache()->getItem('foo');
        if (!$foo->isHit()) {
            $foo->set('bar');
        }
        $this->assertEquals('bar', $foo->get());
    }

    public function testHandleRequest()
    {
        $response = $this->app->handleRequest($this->request('GET', '/'));
        $this->equalsJson($response, ['foo' => 'bar']);
    }

    public function testHandleMiddleware()
    {
        $request = $this->request('GET', '/');
        $response = $this->handleRequest($request);
        $this->assertArrayHasKey('x-cache', $response->getHeaders());
    }

    public function testHandleException()
    {
        $logFile = app()->getPath().'/runtime/logs/info.log';
        $exception = new LogicException('handle exception');
        $response = $this->app->handleException($exception);
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $response);
        $this->app->add('response', $response);
        $this->app->add('request', new \FastD\Http\ServerRequest('GET', '/'));
        $this->app->shutdown(new \FastD\Http\ServerRequest('GET', '/'), $response);
        $this->equalsStatus($response, 500);
        $this->assertFileExists($logFile);
    }

    public function testHandleResponse()
    {
        $response = json([
            'foo' => 'bar',
        ]);
        $this->app->handleResponse($response);
        $this->expectOutputString((string) $response->getBody());
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $response);
    }

    public function testApplicationShutdown()
    {
        $request = $this->request('GET', '/');
        $response = $this->handleRequest($request);
        $this->app->shutdown($request, $response);
    }

    public function testOrdinaryControllerLogic()
    {
        $request = $this->request('GET', '/');
        $response = $this->handleRequest($request);
        $this->equalsStatus($response, 200);
    }

    public function testAbortControllerLogic()
    {
        $request = $this->request('GET', '/abort');
        $response = $this->handleRequest($request);
        $this->equalsStatus($response, 400);
    }

    public function tearDown()
    {
        $logFile = app()->getPath().'/runtime/logs/info.log';
        if (file_exists($logFile)) {
            unlink($logFile);
        }
    }

    public function testSymfonyResponse()
    {
        app()->handleResponse(new \Symfony\Component\HttpFoundation\Response('hello'));
        $this->expectOutputString('hello');
    }
}
