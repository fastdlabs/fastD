<?php

namespace FastD\Server\Events;

use Swoole\Server;

trait OnWorkerStarted
{
    public function onWorkerStart(Server $server, int $id): void
    {
        parent::onWorkerStart($server, $id);

        // 重置 cache 和 db 链接
        if (app()->has('cache')) {
            app()->get('cache')->initConnections();
        }
        if (app()->has('medoodb')) {
            app()->get('medoodb')->initConnections();
        }
    }
}