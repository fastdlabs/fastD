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
        \FastD\ServiceProvider\LoggerServiceProvider::class,
        \FastD\ServiceProvider\DatabaseServiceProvider::class,
        \FastD\ServiceProvider\CacheServiceProvider::class,
        \FastD\ServiceProvider\MoltenServiceProvider::class,
        \ServiceProvider\FooServiceProvider::class,
    ],

    /*
     * Http middleware
     */
    'middleware' => [
        'validator' => [\Middleware\LoginSucessValidator::class],
    ],

    /*
     * Application logger
     */
    'log' => [
        [
            \Monolog\Handler\StreamHandler::class,
            'error.log',
            \FastD\Logger\Logger::ERROR,
        ],
        [
            \Monolog\Handler\StreamHandler::class,
            'info.log',
            \FastD\Logger\Logger::INFO,
        ],
    ],

    /*
     * Exception handle
     */
    'exception' => [
        'response' => function (Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString()),
            ];
        },
        'log' => function (Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString()),
            ];
        },
    ],
];
