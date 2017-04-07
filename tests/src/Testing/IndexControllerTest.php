<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */
use FastD\Application;
use FastD\Test\TestCase;

class IndexControllerTest extends TestCase
{
    public function createApplication()
    {
        return new Application(getcwd().'/tests');
    }

    public function testSayHello()
    {
        $request = $this->request('GET', '/');

        $response = $this->app->handleRequest($request);

        $this->response($response, json_encode(['foo' => 'bar']));
    }

    public function testDb()
    {
        $response = $this->app->handleRequest($this->request('GET', '/db'));

        $this->status($response, 200);
    }
}
