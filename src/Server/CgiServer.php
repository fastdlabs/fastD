<?php

declare(strict_types=1);

namespace FastD\Runtime;

use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Json;
use FastD\Http\Response\StatusCode;
use FastD\Runtime;
use FastD\Terminal;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class CgiServer extends Runtime
{
    public function onInput(): ResponseInterface
    {
        return static::$application->dispatch(ServerRequest::fromGlobals());
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
        error($throwable->getMessage(), $data);
        (new Json(StatusCode::HTTP_INTERNAL_SERVER_ERROR, $data))->send();
    }
}
