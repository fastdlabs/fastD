<?php

return [
    'name' => 'fastd',
    'timezone' => 'Asia/Shanghai',
    'root' => __DIR__ . '/../../',
    'log' => [
        'path' => __DIR__ . '/../../runtime/logs',
        'level' => \Monolog\Level::Info,
    ],
    'route' => __DIR__ . '/../config/routes-session.php',
    'service' => __DIR__ . '/../config/services-session.php',
    'listener' => __DIR__ . '/../config/listeners.php',
    'session' => __DIR__ . '/../config/redis/session.php',
];
