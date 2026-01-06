<?php

declare(strict_types=1);

use FastD\Config\FileParser;
use FastD\Container\Container;
use FastD\Http\Response\JsonResponse;
use FastD\Http\Response\Response;
use FastD\Http\Uri;
use FastD\Runtime;
use Monolog\Logger;

function container(): Container
{
    return Runtime::container();
}

function runtime(): Runtime
{
    return container()->get('runtime');
}

function config(): FileParser
{
    return container()->get('config');
}

function logger(): Logger
{
    return container()->get('logger');
}

function info(string $message, array $context = []): void
{
    logger()->info($message, $context);
}

function debug(string $message, array $context = []): void
{
    logger()->debug($message, $context);
}

function forward(string $method, string $path): Response
{
    $request = clone container()->get('request');
    $request
        ->withMethod($method)
        ->withUri(new Uri($path))
    ;
    $response = app()->get('dispatcher')->dispatch($request);
    unset($request);

    return $response;
}

function abort(string $message, int $statusCode = Response::HTTP_BAD_REQUEST): void
{
    throw new HttpException((empty($message) ? \FastD\Http\Response\StatusCodeInterface::$statusTexts[$statusCode] : $message), $statusCode);
}

function json(array $content = [], int $statusCode = Response::HTTP_OK): JsonResponse
{
    return new JsonResponse($content, $statusCode);
}
