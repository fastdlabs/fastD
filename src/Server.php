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
     * @param SwooleServerRequest $serverRequest
     * @return Response
     */
    public function doRequest(SwooleServerRequest $serverRequest)
    {
        return $this->app->handleHttpRequest($serverRequest);
    }
}