<?php

declare(strict_types=1);

namespace FastD\Server;

use FastD\Http\Exception\HttpException;
use FastD\Http\Request\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use FastD\Http\Response\StatusCode;
use FastD\Http\Response\Json;
use FastD\Runtime;
use FastD\Terminal;
use Throwable;

class CgiServer extends Runtime
{
    public function onInput(): ResponseInterface
    {
        return static::$application->dispatch(ServerRequest::createServerRequestFromGlobals());
    }

    public function onOutput(mixed $output): void
    {
        $output->send();
    }

    public function onError(Throwable $throwable): void
    {
        $data = [
            'msg' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
            'trace' => explode(PHP_EOL, $throwable->getTraceAsString()),
        ];

        $statusCode = StatusCode::HTTP_INTERNAL_SERVER_ERROR;
        if ($throwable instanceof HttpException) {
            $statusCode = $throwable->getStatusCode();
        }

        (new Json($data, $statusCode))->send();
    }
}
