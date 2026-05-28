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

    /*
     * The application root path.
     */
    'root' => __DIR__ . '/../../',

    /*
     * Logging configuration.
     */
    'log' => [
        'path' => __DIR__ . '/../../runtime/logs',
        'level' => \Monolog\Level::Info,
    ],

    /*
     * Route configuration file.
     */
    'route' => __DIR__ . '/../config/routes.php',

    /*
     * Service provider configuration file.
     */
    'service' => __DIR__ . '/../config/services.php',

    /*
     * Event listener configuration file.
     */
    'listener' => __DIR__ . '/../config/listeners.php',

    /*
     * Process configuration file.
     */
    'process' => __DIR__ . '/../config/process.php',
];
