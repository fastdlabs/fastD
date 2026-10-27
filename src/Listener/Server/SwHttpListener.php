<?php

declare(strict_types=1);

namespace FastD\Listener\Server;

use FastD\Application;
use FastD\Swoole\Listener\Server\RequestListener;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SwHttpListener extends RequestListener
{
    public function __construct(private ?Application $application = null)
    {
    }

    public function onRequest(ServerRequestInterface $serverRequest): ResponseInterface
    {
        return ($this->application ?? Application::$application)->dispatch($serverRequest);
    }
}