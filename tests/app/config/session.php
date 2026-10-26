<?php

return [
    'enabled' => true,
    'handler' => 'auto',
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
    'gc_probability' => 0,
];
