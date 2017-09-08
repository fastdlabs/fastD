<?php

    use FastD\Application;
    use ServiceProvider\FooServiceProvider;

    /**
     * @author    jan huang <bboyjanhuang@gmail.com>
     * @copyright 2016
     *
     * @see      https://www.github.com/janhuang
     * @see      http://www.fast-d.cn/
     */
    class NoCacheTest extends \FastD\TestCase
    {
        public function createApplication()
        {
            $app = new Application(__DIR__.'/app/no-cache');

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
            $this->assertTrue(file_exists(app()->getPath().'/runtime/logs/error.log'));
            unlink(app()->getPath().'/runtime/logs/error.log');
        }

        /**
         * @expectedException \LogicException
         */
        public function testCacheServiceProvider()
        {
            cache();
        }

        public function testHandleRequest()
        {
            $response = $this->app->handleRequest($this->request('GET', '/'));
            $this->equalsJson($response, ['foo' => 'bar']);
        }

        public function testHandleException()
        {
            $exception = new LogicException('handle exception');
            $this->app->handleException($exception);
            $response = $this->app->renderException($exception);
            $this->app->add('response', $response);
            $this->app->add('request', new \FastD\Http\ServerRequest('GET', '/'));
            $this->app->shutdown(new \FastD\Http\ServerRequest('GET', '/'), $response);
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
