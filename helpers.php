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
use FastD\Http\HttpException;
use FastD\Http\JsonResponse;
use FastD\Http\Response;
use FastD\Http\Uri;
use fastd\runtime;
use Monolog\Logger;


function app(): Application
{
    return Runtime::application();
}

function runtime(): Runtime
{
    return app()->get('runtime');
}

/**
 * @param string $message
 * @param array $context
 * @return bool
 */
function logging($level, string $message, array $context = []): bool
{
    $configLevel = config()->get('log.level');
    if ($level >= $configLevel) {
        return app()->get('logger')->addRecord(
            $level,
            $message,
            $context
        );
    }
    return false;
}

/**
 * @return Config
 */
function config(): Config
{
    return app()->get('config');
}

/**
 * @param $method
 * @param $path
 *
 * @return Response
 */
function forward(string $method, string $path): Response
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
 * @param string $message
 * @param int $statusCode
 * @return void
 */
function abort(string $message, int $statusCode = Response::HTTP_BAD_REQUEST): void
{
    throw new HttpException((empty($message) ? Response::$statusTexts[$statusCode] : $message), $statusCode);
}

/**
 * @param array $content
 * @param int $statusCode
 * @return JsonResponse
 */
function json(array $content = [], int $statusCode = Response::HTTP_OK): JsonResponse
{
    return new JsonResponse($content, $statusCode);
}
