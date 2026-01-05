<?php

declare(strict_types=1);

use FastD\Application;
use fastd\Environment;
use FastD\Http\Response\JsonResponse;
use FastD\Http\Response\Response;
use FastD\Http\Uri;
use Monolog\Logger;

function app(): Application
{
    return Environment::application();
}

function runtime(): Environment
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

function config(): \FastD\Config\FileParser
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
    throw new HttpException((empty($message) ? \FastD\Http\Response\StatusCodeInterface::$statusTexts[$statusCode] : $message), $statusCode);
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
