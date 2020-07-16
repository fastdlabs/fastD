<?php
    /**
     * @author    jan huang <bboyjanhuang@gmail.com>
     * @copyright 2016
     *
     * @see      https://www.github.com/janhuang
     * @see      https://fastdlabs.com
     */

use FastD\Application;
use FastD\Config\Config;
use FastD\Http\JsonResponse;
use FastD\Http\Response;
use FastD\Http\Uri;
use FastD\Model\Database;
use FastD\Model\Model;
use FastD\Model\ModelFactory;
use FastD\Packet\Swoole;
use FastD\Routing\RouteCollection;
use FastD\Servitization\Client\Client;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

/**
 * @return Application
 */
function app()
{
    return Application::$app;
}

/**
 * @return string
 */
function version()
{
    return Application::VERSION;
}

/**
 * @return RouteCollection
 */
function route()
{
    return app()->get('router');
}

/**
 * @return Config
 */
function config()
{
    return app()->get('config');
}

/**
 * @return ServerRequestInterface
 */
function request()
{
    return app()->get('request');
}

/**
 * @return Response
 */
function response()
{
    if (!app()->has('response')) {
        app()->add('response', new Response());
    }

    return app()->get('response');
}

/**
 * @param $method
 * @param $path
 *
 * @return Response
 */
function forward($method, $path)
{
    $request = clone app()->get('request');
    $request
        ->withMethod($method)
        ->withUri(new Uri($path))
    ;
    $response = app()->get('dispatcher')->dispatch($request);
    unset($request);

    return $response;
}

/**
 * @param $statusCode
 * @param $message
 *
 * @throws Exception
 */
function abort($message, $statusCode = Response::HTTP_BAD_REQUEST)
{
    throw new Exception((is_null($message) ? Response::$statusTexts[$statusCode] : $message), $statusCode);
}

/**
 * @param array $content
 * @param int   $statusCode
 *
 * @return Response
 */
function binary(array $content, $statusCode = Response::HTTP_OK)
{
    return new Response(Swoole::encode($content), $statusCode);
}

/**
 * @param array $content
 * @param int   $statusCode
 *
 * @return Response
 */
function json(array $content = [], $statusCode = Response::HTTP_OK)
{
    return new JsonResponse($content, $statusCode);
}

/**
 * @return Logger
 */
function logger()
{
    return app()->get('logger');
}

/**
 * @param null $uri
 * @param bool $async
 * @param bool $keep
 *
 * @return Client
 */
function client($uri = null, $async = false, $keep = false)
{
    if (null !== $uri) {
        return new Client($uri, $async, $keep);
    }

    return app()->get('client');
}

/**
 * @return \FastD\Swoole\Server
 */
function server()
{
    return app()->get('server');
}

