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
        $app = $this->createApplication();

        $this->assertEquals('fast-d', $app->getName());
        $this->assertTrue($app->isBooted());
    }

    public function testHandleRequest()
    {
        $app = $this->createApplication();
        $response = $app->handleRequest($this->request('GET', '/'));
        $this->assertEquals(json_encode(['foo' => 'bar'], TestCase::JSON_OPTION), $response->getBody());
    }

    public function testHandleDynamicRequest()
    {
        $app = $this->createApplication();
        $response = $app->handleRequest($this->request('GET', '/foo/bar'));
        $this->assertEquals(json_encode(['foo' => 'bar'], TestCase::JSON_OPTION), $response->getBody());
        $response = $app->handleRequest($this->request('GET', '/foo/foobar'));
        $this->assertEquals(json_encode(['foo' => 'foobar'], TestCase::JSON_OPTION), $response->getBody());
    }

    public function testHandleMiddlewareRequest()
    {
        $app = $this->createApplication();
        $response = $app->handleRequest($this->request('POST', '/foo/bar'));
        $this->assertEquals(json_encode(['foo' => 'middleware'], TestCase::JSON_OPTION), $response->getBody());
        $response = $app->handleRequest($this->request('POST', '/foo/not'));
        $this->assertEquals(json_encode(['foo' => 'bar'], TestCase::JSON_OPTION), $response->getBody());
    }

    public function testServiceProvider()
    {
        $app = $this->createApplication();
        $app->register(new FooServiceProvider());
        $this->assertEquals('foo', $app['foo']->name);
    }

    public function testConfigurationServiceProvider()
    {
        $app = $this->createApplication();
        $this->assertEquals('fast-d', $app->get('config')->get('name'));
        $this->assertNull(config()->get('foo'));
    }

    public function testLoggerServiceProvider()
    {
        $app = $this->createApplication();

        $request = $this->request('GET', '/');
        $response = $app->handleRequest($request);
        $this->assertEquals(200, $response->getStatusCode());

        $request = $this->request('GET', '/not/found');
        $response = $app->handleRequest($request);
        $this->assertEquals(404, $response->getStatusCode());
//        $this->assertTrue(file_exists(app()->getPath().'/runtime/logs/error.log'));
    }

    public function testCacheServiceProvider()
    {
        $app = $this->createApplication();

        $this->assertInstanceOf(FilesystemAdapter::class, $app->get('cache')->getCache('default'));
    }

    public function testModel()
    {
        $app = $this->createApplication();

        $response = $app->handleRequest($this->request('GET', '/model'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->isSuccessful($response);
    }

    public function testAuth()
    {
        $app = $this->createApplication();

        $response = $app->handleRequest($this->request('GET', '/auth'));

        $this->assertEquals(401, $response->getStatusCode());

        $this->assertEquals(json_encode([
            'msg' => 'not allow access',
            'code' => 401,
        ], TestCase::JSON_OPTION), (string) $response->getBody());

        $response = $app->handleRequest($this->request('GET', 'http://foo:bar@example.com/auth', [
            'PHP_AUTH_USER' => 'foo',
            'PHP_AUTH_PW' => 'bar',
        ]));

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals(json_encode([
            'foo' => 'bar',
        ], TestCase::JSON_OPTION), (string) $response->getBody());
    }
}
