<?php

declare(strict_types=1);

use FastD\Application;
use FastD\Config\FileParser;
use FastD\Container\Container;
use FastD\Event\EventDispatcher;
use FastD\Http\Response\Json;
use FastD\Http\Response\StatusCode;
use FastD\Http\Response\Text;
use FastD\Http\Uri;
use FastD\Runtime;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;

function container(): Application
{
    return Application::$application;
}

function config(): FileParser
{
    return container()->get('config');
}

function event(): EventDispatcher
{
    return container()->get('event');
}

function logger(): Logger
{
    return container()->get('logger');
}

function debug(string $message, array $context = []): void
{
    logger()->debug($message, $context);
}

function info(string $message, array $context = []): void
{
    logger()->info($message, $context);
}

function error(string $message, array $context = []): void
{
    logger()->error($message, $context);
}

function logging(int $level, string $message, array $context = []): void
{
    logger()->log($level, $message, $context);
}

function forward(string $method, string $path): ResponseInterface
{
    $request = container()->get('request')
        ->withMethod($method)
        ->withUri(new Uri($path))
    ;
    $response = container()->get('dispatcher')->dispatch($request);
    unset($request);

    return $response;
}

function abort(string $message, int $statusCode = StatusCode::HTTP_BAD_REQUEST): void
{
    throw new HttpException((empty($message) ? StatusCode::PHRASES[$statusCode] : $message), $statusCode);
}

function text(string $content = '', int $statusCode = StatusCode::HTTP_OK): ResponseInterface
{
    return new Text($statusCode, $content);
}

function json(array $content = [], int $statusCode = StatusCode::HTTP_OK): ResponseInterface
{
    return new Json($statusCode, $content);
}
