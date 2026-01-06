<?php

namespace FastD\Server\Events;

use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Response;
use FastD\Swoole\Server\HTTP;

trait OnResponsed
{
    public function onResponse(ServerRequest $serverRequest): Response
    {
        return container()->dispatch($serverRequest);
    }
}