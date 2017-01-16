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
use FastD\Http\ServerRequest;
use FastD\ServiceProvider\SwooleServiceProvider;
use FastD\Swoole\Server\Http;
use swoole_http_response;

/**
 * Class App
 *
 * @package FastD
 */
class Server extends Http
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Server constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $application->register(new SwooleServiceProvider());

        $this->app = $application;

        parent::__construct($application->getName(), $application['config']->get('listen'));
    }

    /**
     * @param ServerRequest $serverRequest
     * @return Response
     */
    public function doRequest(ServerRequest $serverRequest)
    {
        return app()->handleRequest($serverRequest);
    }

    /**
     * Please return swoole configuration array.
     *
     * @return array
     */
    public function configure()
    {
        return app()['config']->all();
    }
}