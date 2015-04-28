<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/27
 * Time: 下午12:23
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Framework\Tests;

use Dobee\Http\Request;
use Dobee\Http\Response;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Application
     */
    private $app;

    public function setUp()
    {
        if (!class_exists('\\Application')) {
            include __DIR__ . '/../../../../application/Application.php';
        }

        $this->app = new \Application('local');
    }

    public function testContainer()
    {
        $this->app->initializeContainer();

        $this->assertEquals($this->app->getContainer()->get('kernel'), $this->app);
    }

    public function testException()
    {
        $this->testConfiguration();
    }

    public function testConfiguration()
    {
        $this->testContainer();

        $this->app->initializeConfigure();

        $this->assertInstanceOf('Dobee\\Config\\Config', $this->app->getContainer()->get('kernel.config'));

        $this->assertEquals('read', $this->app->getContainer()->get('kernel.config')->getParameters('database.default_connection'));
    }

    public function testRoutes()
    {
        $this->testException();

        $this->app->initializeRouting();

        $this->assertEquals($this->app->getContainer()->get('kernel.routing'), \Routes::getRouter());

        $this->assertEquals('welcome', $this->app->getContainer()->get('kernel.routing')->getRoute('welcome')->getName());
        $this->assertEquals('/', $this->app->getContainer()->get('kernel.routing')->getRoute('welcome')->getPath());
    }

    public function testRequest()
    {
        $this->testRoutes();

        $request = Request::createGlobalRequest();
        $request->server->set('PATH_INFO', '/');

        $response = $this->app->handleHttpRequest($request);

        $this->assertEquals($response, new Response('hello world'));

        $this->assertEquals($response->getContent(), 'hello world');

        $request->server->set('PATH_INFO', '/demo');
        $response = $this->app->handleHttpRequest($request);
        $this->assertEquals('hello world', $response->getContent());
    }

    public function testDB()
    {
        $this->testRoutes();

        $request = Request::createGlobalRequest();

        $request->server->set('PATH_INFO', '/db');
        $response = $this->app->handleHttpRequest($request);

        $this->assertEquals(2, $response->getContent());
    }
}