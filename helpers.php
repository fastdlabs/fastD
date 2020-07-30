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
use FastD\Container\Container;
use FastD\Http\JsonResponse;
use FastD\Http\Response;
use FastD\Http\Uri;
use FastD\Routing\RouteCollection;
use FastD\Runtime\Runtime;
use Monolog\Logger;


/**
 * @return Application
 */
function app(): Application
{
    return Runtime::$application;
}

function container(): Container
{
    return Runtime::$container;
}

/**
 * @return string
 */
function version(): string
{
    return Application::VERSION;
}

/**
 * @return RouteCollection
 */
function route(): RouteCollection
{
    return container()->get('router');
}

/**
 * @return Config
 */
function config(): Config
{
    return container()->get('config');
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
