<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */


use FastD\Test\TestCase;
use Http\Controller\IndexController;


class IndexControllerTest extends TestCase
{
    public function setUp()
    {
        $this->app = new \FastD\Application(getcwd() . '/tests');
    }

    public function testSayHello()
    {
        $request = $this->request('GET', '/');

        $response = $this->app->handleRequest($request);

        $this->response($response, ['foo' => 'bar']);
    }
}
