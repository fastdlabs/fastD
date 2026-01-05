<?php

return [
    /*
     * The application name.
     */
    'name' => 'fastd',

    /*
     * The application timezone.
     */
    'timezone' => 'PRC',

    /**
     * logging
     */
    'log' => [
        'level' => \Monolog\Level::Info,
    ],
    'routes' => __DIR__ . '/routes.php',

    'services' => __DIR__ . '/services.php',
];
