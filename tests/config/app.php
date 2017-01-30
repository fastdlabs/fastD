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
    'name' => 'Fast-D',

    /**
     * Application environment local/dev/prod
     */
    'environment' => 'prod',

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
     * Bootstrap service.
     */
    'services' => [
        \FastD\ServiceProvider\DatabaseServiceProvider::class,
        \FastD\ServiceProvider\CacheServiceProvider::class,
    ],

    /**
     * Http middleware
     */
    'middleware' => [
        'auth' => \FastD\Auth\BasicAuth::class
    ]
];