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
use FastD\ServiceProvider\SwooleServiceProvider;
use FastD\Swoole\Server\Http;
use swoole_http_request;
use swoole_http_response;

/**
 * Class App
 *
 * @package FastD
 */
class Server extends Http
{
    /**
     * @var array
     */
    protected $config = [];

    public function __construct(Application $application)
    {
        $application->register(new SwooleServiceProvider());

        $config = include app()->getAppPath() . '/config/server.php';

        parent::__construct($config['listen'], isset($config['options']) ? $config['options'] : []);
    }

    /**
     * @param SwooleServerRequest $serverRequest
     * @return Response
     */
    public function doRequest(SwooleServerRequest $serverRequest)
    {
        return app()->handleRequest($serverRequest);
    }
}