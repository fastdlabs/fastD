<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Framework\Tests;

use FastD\Container\Container;
use FastD\Http\Request;
use FastD\Http\Response;

/**
 * Class TestClient
 *
 * @package FastD\Framework\Tests
 */
class TestClient
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Request
     */
    protected $request;

    /**
     * TestClient constructor.
     * @param Container $container
     * @param Request|null $request
     */
    public function __construct(Container $container, Request $request = null)
    {
        $this->container = $container;

        if (null !== $request) {
            $this->request = $request;
        }
    }

    /**
     * @param       $method
     * @param       $path
     * @param array $parameters
     * @return Response
     */
    public function testResponse($method, $path, array $parameters = [])
    {
        $this->request->server->set('REQUEST_METHOD', strtoupper($method));
        $this->setPathInfo($path);

        return $this->container->singleton('kernel.dispatch')->dispatch('handle.http', array_merge([$this->request], $parameters));
    }

    protected function setPathInfo($pathinfo)
    {
        $server = $this->request->server;

        $reflection = new \ReflectionClass($server);

        $prototype = $reflection->getProperty('pathInfo');
        $prototype->setAccessible(true);
        $prototype->setValue($server, $pathinfo);

        unset($reflection);

        $this->request->server = $server;
    }
}