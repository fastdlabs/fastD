<?php


return [
    'host' => 'http://0.0.0.0:9999',
    'handle' => HttpHandler::class, // 事件监听处理器
    // swoole server 配置项，与官网保持一致: https://wiki.swoole.com/#/server/setting
    'options' => [
        'log_level' => 5,
        'worker_num' => 8,
    ],
];
