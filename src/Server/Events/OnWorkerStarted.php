<?php

namespace FastD\Server\Events;

use Swoole\Server;

trait OnWorkerStarted
{
    public function onWorkerStart(Server $server, int $id): void
    {
        parent::onWorkerStart($server, $id);
    }
}