<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

return [
    /*
     * The application name.
     */
    'name' => 'fast-d',

    /*
     * Application logger
     */
    'log' => [
        [\Monolog\Handler\StreamHandler::class, 'info.log'],
        [\Monolog\Handler\StreamHandler::class, 'error.log', \FastD\Logger\Logger::ERROR],
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

    /*
     * Bootstrap default service provider
     */
    'services' => [
        \FastD\ServiceProvider\RouteServiceProvider::class,
        \FastD\ServiceProvider\LoggerServiceProvider::class,
        \FastD\ServiceProvider\DatabaseServiceProvider::class,
        \FastD\ServiceProvider\CacheServiceProvider::class,
    ],

    /*
     * Consoles
     */
    'consoles' => [
    ],

    /*
     * Http middleware
     */
    'middleware' => [
        'basic.auth' => new FastD\BasicAuthenticate\HttpBasicAuthentication(
            [
                'authenticator' => [
                    'class' => \FastD\BasicAuthenticate\PhpAuthenticator::class,
                    'params' => [
                        'foo' => 'bar',
                    ],
                ],
                'response' => [
                    'class' => \FastD\Http\JsonResponse::class,
                    'data' => [
                        'msg' => 'not allow access',
                        'code' => 401,
                    ],
                ],
            ]
        ),
        'common.cache' => [
            \FastD\Middleware\CacheMiddleware::class,
        ],
    ],
];
