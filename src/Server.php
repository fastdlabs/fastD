<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;

use FastD\Http\Response;
use FastD\Http\SwooleServerRequest;
use FastD\Swoole\Http\HttpServer;
use swoole_http_request;
use swoole_http_response;

/**
 * Class App
 *
 * @package FastD
 */
class Server extends HttpServer
{
    /**
     * @var App
     */
    protected $app;

    /**
     * AppServer constructor.
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;

        parent::__construct($app->getContainer()->singleton('kernel.config')->get('server'));
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->app->isDebug();
    }

    /**
     * @param SwooleServerRequest $serverRequest
     * @return Response
     */
    public function doRequest(SwooleServerRequest $serverRequest)
    {
        $response = $this->app->handleHttpRequest($serverRequest);

        return $response;
    }

    /**
     * @param swoole_http_request $swooleRequet
     * @param swoole_http_response $swooleResponse
     */
    public function onRequest(swoole_http_request $swooleRequet, swoole_http_response $swooleResponse)
    {
        parent::onRequest($swooleRequet, $swooleResponse);

        $this->app->shutdown();
    }
}