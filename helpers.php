<?php

declare(strict_types=1);

use FastD\Application;
use FastD\Config\FileParser;
use FastD\Container\Container;
use FastD\Event\EventDispatcher;
use FastD\Http\Response\Json;
use FastD\Http\Response\SseResponse;
use FastD\Http\Response\StatusCode;
use FastD\Http\Response\Text;
use FastD\Http\Session\SessionInterface;
use FastD\Http\Session\SessionMiddleware;
use FastD\Http\Sse\EmitterInterface;
use FastD\Http\Uri;
use FastD\Runtime;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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

function forward(ServerRequestInterface $request, string $method, string $path): ResponseInterface
{
    return container()->get('matcher')->dispatch(
        $request->withMethod($method)->withUri(new Uri($path))
    );
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

/**
 * 从当前请求读取会话对象（需先启用 SessionServiceProvider）。
 *
 * 会话对象是 per-request 的，因此必须显式传入请求，不能放在容器里做单例。
 */
function session(ServerRequestInterface $request): ?SessionInterface
{
    $session = $request->getAttribute(SessionMiddleware::ATTRIBUTE);

    return $session instanceof SessionInterface ? $session : null;
}

/**
 * 创建 Server-Sent Events 流式响应。
 *
 * 支持迭代器（yield 事件）或回调（EmitterInterface $emitter）两种写法，
 * CGI/FPM 与 Swoole 运行时由框架自动选择输出方式。
 *
 * @param iterable<\FastD\Http\Sse\Event|string|array>|(\Closure(EmitterInterface): void) $producer
 * @param array<string, mixed> $options 见 SseResponse 构造函数（heartbeat/max_duration/retry/error_event/headers/on_error）
 */
function sse(iterable|\Closure $producer, array $options = []): SseResponse
{
    return new SseResponse($producer, $options);
}
