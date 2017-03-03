<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

return [
    /**
     * The application name.
     */
    'name' => 'fast-d',

    /**
     * Run environment
     */
    'environment' => 'dev',

    /**
     * Application timezone
     */
    'timezone' => 'PRC',

    /**
     * Application logger path
     */
    'log' => [
        'path' => 'storage',
        'info' => \Monolog\Handler\StreamHandler::class, // 访问日志
        'error' => \Monolog\Handler\StreamHandler::class, // 错误日志
    ],

    /**
     * Bootstrap service
     */
    'services' => [
        \FastD\ServiceProvider\DatabaseServiceProvider::class,
        \FastD\ServiceProvider\CacheServiceProvider::class,
    ],

    /**
     * Consoles
     */
    'consoles' => [

    ],

    /**
     * Http middleware
     */
    'middleware' => [
        'basic.auth' => new FastD\BasicAuthenticate\HttpBasicAuthentication([
            'authenticator' => [
                'class' => \FastD\BasicAuthenticate\PhpAuthenticator::class,
                'params' => [
                    'foo' => 'bar'
                ]
            ],
            'response' => [
                'class' => \FastD\Http\JsonResponse::class,
                'data' => [
                    'msg' => 'not allow access',
                    'code' => 401
                ]
            ]
        ])
    ],
];