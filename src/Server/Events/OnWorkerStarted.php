<?php

namespace FastD\Server\Events;

use Swoole\Server;

trait OnWorkerStarted
{
    public function onWorkerStart(Server $server, int $id): void
    {
        parent::onWorkerStart($server, $id);

        // 当容器中存在 workerStart 需要执行的回调时，则进行处理
        if (container()->has('onWorkerStart')) {
            $events = container()->get('onWorkerStart');
            foreach ($events['service'] as $event) {
                if ($event instanceof CallbackEventsInterface) {
                    $result = $event->onCallback();
                    debug('connect ' . ($result ? 'successful' : 'failed'), [
                        'class' => get_class($event),
                    ]);
                }
            }
        }
    }
}