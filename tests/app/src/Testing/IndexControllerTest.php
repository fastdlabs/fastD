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

    class IndexControllerTest extends TestCase
    {
        public function createApplication()
        {
            return new Application(__DIR__.'/../../default');
        }

        public function testSayHello()
        {
            $response = $this->app->handleRequest($this->request('GET', '/'));
            $this->equalsJson($response, ['foo' => 'bar']);
            $response = $this->app->handleRequest($this->request('GET', '/?foo=var'));
            $this->equalsJson($response, ['foo' => 'var']);
            $response = $this->app->handleRequest($this->request('GET', '/?foo=bar'));
            $this->equalsJson($response, ['foo' => 'bar']);
        }

        public function testDb()
        {
            $response = $this->app->handleRequest($this->request('GET', '/db'));

            $this->equalsStatus($response, 200);
        }

        public function testHandleDynamicRequest()
        {
            $response = $this->app->handleRequest($this->request('GET', '/foo/bar'));
            $this->equalsJson($response, ['foo' => 'bar']);
            $response = $this->app->handleRequest($this->request('GET', '/foo/foobar'));
            $this->equalsJson($response, ['foo' => 'foobar']);
        }

        public function testHandleMiddlewareRequest()
        {
            $response = $this->app->handleRequest($this->request('POST', '/foo/bar'));
            $this->equalsJson($response, ['foo' => 'middleware']);
            $response = $this->app->handleRequest($this->request('POST', '/foo/not'));
            $this->equalsJson($response, ['foo' => 'bar']);
        }

        public function testModel()
        {
            $response = $this->app->handleRequest($this->request('GET', '/model'));
            $this->assertEquals(200, $response->getStatusCode());
            $this->isSuccessful($response);
        }

        public function testAuth()
        {
            $response = $this->app->handleRequest($this->request('GET', '/auth'));

            $this->assertEquals(401, $response->getStatusCode());

            $this->equalsJson($response, [
                              'msg' => 'not allow access',
                              'code' => 401,
                              ]);

            $response = $this->app->handleRequest($this->request('GET', 'http://foo:bar@example.com/auth', [
                                                                 'PHP_AUTH_USER' => 'foo',
                                                                 'PHP_AUTH_PW' => 'bar',
                                                                 ]));

            $this->assertEquals(200, $response->getStatusCode());

            $this->equalsJson($response, [
                              'foo' => 'bar',
                              ]);
        }
    }
