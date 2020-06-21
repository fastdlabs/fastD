<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

return [
    /*
     * The application name.
     */
    'name' => 'fast-d',

    /*
     * The application timezone.
     */
    'timezone' => 'PRC',

    /*
     * Bootstrap default service provider
     */
    'services' => [
        \FastD\ServiceProvider\RouteServiceProvider::class,
    ],

    /**
     * Exception Handler
     */
    'exception' => [
        'service' => \FastD\ServiceProvider\ExceptionServiceProvider::class,
        'adapter' => \FastD\Exception\ExceptionHandler::class,
        'options' => [
            'level' => E_ALL
        ]
    ],

    /**
     * Logger Handler
     */
    'logger' => [
        'service' => \FastD\ServiceProvider\LoggerServiceProvider::class,
        // 日志驱动，系统发生日志读写时触发
        'default' => [
            'handler' => \FastD\Logger\AccessHandler::class,
            'level' => \Monolog\Logger::DEBUG,
            'formatter' => \FastD\Logger\Formatter\StashFormatter::class,
        ],
    ],
];
