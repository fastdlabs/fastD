<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;

use FastD\Http\Swoole\SwooleRequest;
use FastD\Http\Response;
use FastD\Swoole\Http\HttpServer;
use FastD\Swoole\Server\Server;

/**
 * Class App
 *
 * @package FastD
 */
class AppServer extends HttpServer
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

        $this->app->bootstrap();

        parent::__construct($app->getContainer()->get('kernel.config')->get('server'));
    }

    /**
     * @param SwooleRequest $request
     * @return Response
     */
    public function doRequest(SwooleRequest $request)
    {
        return $this->app->createHttpRequestHandler($request);
    }

    /**
     * @return Server
     */
    public function bootstrap()
    {
        return parent::bootstrap();
    }
}