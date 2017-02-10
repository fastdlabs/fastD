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
        'basic.auth' => \FastD\Auth\BasicAuthenticationMiddleware::class
    ],

    /**
     * HTTP basic auth
     */
    'basic.auth' => [
        'secure' => false, // https
        'users' => [
            'foo' => 'bar'
        ]
    ],

    /**
     * Database config
     */
    'database' => include __DIR__ . '/database.php',

    /**
     * Caching config
     */
    'cache' => include __DIR__ . '/cache.php',
];