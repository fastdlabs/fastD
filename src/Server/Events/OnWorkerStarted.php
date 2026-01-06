<?php

namespace FastD\Server\Events;

use Swoole\Server;

trait OnWorkerStarted
{
    public function onWorkerStart(Server $server, int $id): void
    {
        parent::onWorkerStart($server, $id);

        // 重置 cache 和 db 链接
        if (container()->has('cache')) {
            container()->get('cache')->initConnections();
        }
        if (container()->has('medoodb')) {
            container()->get('medoodb')->initConnections();
        }
    }
}