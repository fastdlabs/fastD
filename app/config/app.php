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

    /**
     * Controller namespace
     */
    'namespace' => '\\Controller\\',

    /*
     * Bootstrap default service provider
     */
    'services' => [
        \FastD\ServiceProvider\ConfigServiceProvider::class,
        \FastD\ServiceProvider\RouteServiceProvider::class,
        \FastD\Exception\ExceptionServiceProvider::class,
    ],
];
