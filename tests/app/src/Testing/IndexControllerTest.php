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
use FastD\Http\JsonResponse;

class IndexControllerTest extends TestCase
{
    public function createApplication()
    {
        return new Application(__DIR__.'/../../default');
    }

    public function testSayHello()
    {
        $request = $this->request('GET', '/');

        $response = $this->app->handleRequest($request);

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
        $this->assertEquals(json_encode(['foo' => 'bar'], JsonResponse::JSON_OPTIONS), $response->getBody());
        $response = $this->app->handleRequest($this->request('GET', '/foo/foobar'));
        $this->assertEquals(json_encode(['foo' => 'foobar'], JsonResponse::JSON_OPTIONS), $response->getBody());
    }

    public function testHandleMiddlewareRequest()
    {
        $response = $this->app->handleRequest($this->request('POST', '/foo/bar'));
        $this->assertEquals(json_encode(['foo' => 'middleware'], JsonResponse::JSON_OPTIONS), $response->getBody());
        $response = $this->app->handleRequest($this->request('POST', '/foo/not'));
        $this->assertEquals(json_encode(['foo' => 'bar'], JsonResponse::JSON_OPTIONS), $response->getBody());
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

        $this->assertEquals(json_encode([
            'msg' => 'not allow access',
            'code' => 401,
        ], JsonResponse::JSON_OPTIONS), (string) $response->getBody());

        $response = $this->app->handleRequest($this->request('GET', 'http://foo:bar@example.com/auth', [
            'PHP_AUTH_USER' => 'foo',
            'PHP_AUTH_PW' => 'bar',
        ]));

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals(json_encode([
            'foo' => 'bar',
        ], JsonResponse::JSON_OPTIONS), (string) $response->getBody());
    }
}
