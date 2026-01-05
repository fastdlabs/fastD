<?php

declare(strict_types=1);

namespace FastD\Environment;

use FastD\Application;
use FastD\Http\Exception\HttpException;
use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\JsonResponse;
use FastD\Http\Response\Response;
use FastD\Http\Response\StatusCodeInterface;
use FastD\Routing\Exceptions\RouteException;
use FastD\Routing\Exceptions\RouteNotFoundException;
use FastD\Environment;
use Throwable;

class FastCGI extends Environment
{
    public function __construct(Application $application)
    {
        parent::__construct('fastcgi', $application);
    }

    public function onInput(): Response
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

        $statusCode = StatusCodeInterface::HTTP_INTERNAL_SERVER_ERROR;
        if ($throwable instanceof HttpException) {
            $statusCode = $throwable->getStatusCode();
        }

        (new JsonResponse($data, $statusCode))->send();
    }
}
