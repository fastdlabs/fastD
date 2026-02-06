<?php

declare(strict_types=1);

namespace FastD\Listener;

use FastD\Event\BootedEvent;
use FastD\Swoole\Event\Server\RequestEvent;
use FastD\Swoole\Listener\Server\RequestListener;
use FastD\Swoole\Listener\SwooleEventListener;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SwHttpListener extends RequestListener
{
    public function onRequest(ServerRequestInterface $serverRequest): ResponseInterface
    {
        return container()->dispatch($serverRequest);
    }
}