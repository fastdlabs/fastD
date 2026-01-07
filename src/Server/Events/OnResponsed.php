<?php

namespace FastD\Server\Events;

use Psr\Http\Message\ResponseInterface;
use FastD\Http\Request\ServerRequest;
use FastD\Swoole\Server\HTTP;

trait OnResponsed
{
    public function onResponse(ServerRequest $serverRequest): ResponseInterface
    {
        return container()->dispatch($serverRequest);
    }
}