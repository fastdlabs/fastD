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
use fastd\server\runtime;
use Monolog\Logger;


function container(): Container
{
    return Runtime::$container;
}

function runtime(): Runtime
{
    return container()->get('runtime');
}

/**
 * @return RouteCollection
 */
function router(): RouteCollection
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
function forward(string $method, string $path): Response
{
    $request = clone container()->get('request');
    $request
        ->withMethod($method)
        ->withUri(new Uri($path))
    ;
    $response = container()->get('dispatcher')->dispatch($request);
    unset($request);

    return $response;
}

/**
 * @param $statusCode
 * @param $message
 *
 * @throws Exception
 */
function abort(string $message, int $statusCode = Response::HTTP_BAD_REQUEST): void
{
    throw new Exception((is_null($message) ? Response::$statusTexts[$statusCode] : $message), $statusCode);
}

/**
 * @param array $content
 * @param $statusCode
 * @return JsonResponse
 */
function json(array $content = [], int $statusCode = Response::HTTP_OK): JsonResponse
{
    return new JsonResponse($content, $statusCode);
}

/**
 * @return Logger
 */
function logger(): Logger
{
    return container()->get('logger');
}
