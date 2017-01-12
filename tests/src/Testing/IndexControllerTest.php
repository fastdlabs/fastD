<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */


use FastD\Application;
use FastD\Test\TestCase;


class IndexControllerTest extends TestCase
{
    /**
     * @var Application
     */
    protected $app;

    public function setUp()
    {
        $this->app = new Application(getcwd() . '/tests');
    }

    public function testSayHello()
    {
        $request = $this->request('GET', '/');

        $response = $this->app->handleRequest($request);

        $this->response($response, ['foo' => 'bar']);
    }

    public function testDb()
    {
        $response = $this->app->handleRequest($this->request('GET', '/db'));

        $this->status($response, 200);
    }
}
