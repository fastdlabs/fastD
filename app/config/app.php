<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

use FastD\Http\Response;

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
        \FastD\Providers\ConfigProvider::class,
        \FastD\Providers\ExceptionProvider::class,
        \FastD\Providers\RouteProvider::class,
    ],

    /**
     * Exception Handler
     */
    'exception' => [
        'adapter' => new class
        {
            function handle(Throwable $throwable): Response
            {
                return json([
                    'msg' => $throwable->getMessage(),
                    'code' => $throwable->getCode(),
                    'line' => $throwable->getLine(),
                    'trace' => $throwable->getTraceAsString(),
                ]);
            }
        },
        'options' => [
            'level' => E_ALL
        ]
    ],

    /**
     * Logger Handler
     */
    /*'logger' => [
        // 日志驱动，系统发生日志读写时触发
        'default' => [
            'handler' => \FastD\Logger\AccessHandler::class,
            'level' => \Monolog\Logger::DEBUG,
            'formatter' => \FastD\Logger\Formatter\StashFormatter::class,
        ],
    ],*/
];
