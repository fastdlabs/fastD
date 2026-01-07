<?php

declare(strict_types=1);

use FastD\Application;
use FastD\Config\FileParser;
use FastD\Container\Container;
use FastD\Http\Response\Json;
use FastD\Http\Response\StatusCode;
use FastD\Http\Uri;
use FastD\Runtime;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;

function container(): Application
{
    return Runtime::container();
}

function runtime(): Runtime
{
    return container()->got('runtime');
}

function config(): FileParser
{
    return container()->got('config');
}

function logger(): Logger
{
    return container()->got('logger');
}

function info(string $message, array $context = []): void
{
    logger()->info($message, $context);
}

function debug(string $message, array $context = []): void
{
    logger()->debug($message, $context);
}

function forward(string $method, string $path): ResponseInterface
{
    $request = clone container()->get('request');
    $request
        ->withMethod($method)
        ->withUri(new Uri($path))
    ;
    $response = container()->got('dispatcher')->dispatch($request);
    unset($request);

    return $response;
}

function abort(string $message, int $statusCode = StatusCode::HTTP_BAD_REQUEST): void
{
    throw new HttpException((empty($message) ? StatusCode::STATUS_TEXT[$statusCode] : $message), $statusCode);
}

function json(array $content = [], int $statusCode = StatusCode::HTTP_OK): ResponseInterface
{
    return new Json($content, $statusCode);
}
