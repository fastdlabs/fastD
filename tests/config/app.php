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
     * Application timezone
     */
    'timezone' => 'PRC',

    /*
     * Application logger path
     */
    'log' => [
        [\Monolog\Handler\StreamHandler::class, 'error.log', \Monolog\Logger::ERROR],
        [\Monolog\Handler\StreamHandler::class, 'testCase.log'],
    ],

    /*
     * Bootstrap service
     */
    'services' => [
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
    ],
];
