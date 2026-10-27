<?php

use FastD\Http\Session\Handler\RedisSessionHandler;

return [
    'enabled' => true,
    'handler' => RedisSessionHandler::class,
    'ttl' => 60,
    'cookie' => [
        'name' => 'FASTD_SESSID',
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'http_only' => true,
        'same_site' => 'Lax',
        'lifetime' => 0,
    ],
    'file' => ['path' => __DIR__ . '/../runtime/session'],
    'swoole_table' => ['size' => 64, 'max_data_size' => 1024],
    'redis' => [
        'host' => '127.0.0.1',
        'port' => 1,
        'timeout' => 0.01,
        'prefix' => 'fastd:test:session:',
    ],
    'gc_probability' => 0,
];
