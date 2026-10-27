<?php

return [
    'servers' => [
        'http' => [
            'host' => '127.0.0.1',
            'port' => 9999,
            'handler' => \FastD\Listener\Server\SwHttpListener::class,
        ],
    ],
    'events' => [
        \FastD\Swoole\Listener\Server\WorkerListener::class,
    ],
    'options' => [
        'log_level' => 5,
        'worker_num' => 1,
    ],
];
