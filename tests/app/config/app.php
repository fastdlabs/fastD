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
    'name' => 'fast:D',

    'env' => 'dev',

    'timezone' => 'PRC',

    'database' => include __DIR__ . '/database.php',

    'storage' => include __DIR__ . '/storage.php',

    'middleware' => [

    ],

    /**
     * Bootstrap service.
     */
    'services' => [
        \FastD\Provider\RouteServiceProvider::class,
        \FastD\Provider\StoreServiceProvider::class,
        \FastD\Provider\MiddlewareServiceProvider::class,
    ]
];