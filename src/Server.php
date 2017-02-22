<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;

use Exception;
use FastD\Http\Response;
use FastD\Http\SwooleServerRequest;
use FastD\ServiceProvider\SwooleServiceProvider;
use FastD\Swoole\Server\Http;
use Psr\Http\Message\ServerRequestInterface;
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
     * @var Application
     */
    protected $application;

    /**
     * Server constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;

        $application->register(new SwooleServiceProvider($this));

        parent::__construct($application->getName(), $application->get('config')->get('listen'));

        $this->configure($application->get('config')->get('options'));

        $this->initMultiPorts()->initProcesses();
    }

    /**
     * @return $this
     */
    public function initMultiPorts()
    {
        $ports = $this->application->get('config')->get('ports', []);
        foreach ($ports as $port) {
            $class = $port['class'];
            $this->listen(new $class('ports', $port['listen'], $port['options']));
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function initProcesses()
    {
        $processes = $this->application->get('config')->get('processes', []);
        foreach ($processes as $process) {
            $this->process(new $process);
        }
        return $this;
    }

    /**
     * @param swoole_http_request $swooleRequet
     * @param swoole_http_response $swooleResponse
     */
    public function onRequest(swoole_http_request $swooleRequet, swoole_http_response $swooleResponse)
    {
        try {
            $swooleRequestServer = SwooleServerRequest::createServerRequestFromSwoole($swooleRequet);
            $response = $this->doRequest($swooleRequestServer);
        } catch (Exception $e) {
            $response = $this->application->handleException($e);
        }

        foreach ($response->getHeaders() as $key => $header) {
            $swooleResponse->header($key, $response->getHeaderLine($key));
        }

        foreach ($swooleRequestServer->getCookieParams() as $key => $cookieParam) {
            $swooleResponse->cookie($key, $cookieParam);
        }

        $swooleResponse->status($response->getStatusCode());
        $swooleResponse->end((string) $response->getBody());
        unset($response, $swooleRequestServer, $swooleResponse);
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return Response
     */
    public function doRequest(ServerRequestInterface $serverRequest)
    {
        return app()->handleRequest($serverRequest);
    }
}