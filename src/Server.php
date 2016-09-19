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

use FastD\Console\Input\ArgvInput;
use FastD\Http\Response;
use FastD\Swoole\Http\HttpRequest;
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

        parent::__construct($app->getContainer()->get('kernel.config')->get('server'));
    }

    /**
     * @param HttpRequest $request
     * @return Response
     */
    public function doRequest(HttpRequest $request)
    {
        return $this->html('hello');
    }
}