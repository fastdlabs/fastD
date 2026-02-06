<?php

declare(strict_types=1);

namespace FastD\Server;

use FastD\Event\BootedEvent;
use FastD\Event\AbortEvent;
use FastD\Event\FinishEvent;
use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Json;
use FastD\Http\Response\StatusCode;
use FastD\Runtime;
use FastD\Terminal;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class CgiServer extends Runtime
{
    public function input(): ResponseInterface
    {
        return $this->application->dispatch(ServerRequest::fromGlobals());
    }

    public function output(mixed $output): void
    {
        $output->send();
    }

    public function abort(Throwable $throwable): void
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
