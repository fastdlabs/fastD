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
        $this->assertEquals(date_default_timezone_get(), config()->get('timezone'));
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
            'ServiceProvider\DemoConsole',
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

    public function testHandleRequest()
    {
        $response = $this->app->handleRequest($this->request('GET', '/'));
        $this->equalsJson($response, [
            'foo' => 'bar'
        ]);
    }

    public function testHandleException()
    {
        try {
            $e = new Exception('exception');
            $this->app->handleException($e);
        } catch (Exception $e) {
            $this->assertEquals('exception', $e->getMessage());
        }
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

    public function testSymfonyResponse()
    {
        $response = new \Symfony\Component\HttpFoundation\Response('foo');
        $this->app->handleResponse($response);
        $this->expectOutputString($response->getContent());
    }

    public function testApplicationShutdown()
    {
        $request = $this->request('GET', '/');
        $response = $this->handleRequest($request);
        $this->app->shutdown($request, $response);
    }
}
