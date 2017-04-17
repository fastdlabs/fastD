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
     * Application logger path
     */
    'log' => [
    ],

    /*
     * Exception handle
     */
    'exception' => [
        'handle' => function (Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        },
    ],

    /*
     * Bootstrap default service provider
     */
    'services' => [
        \FastD\ServiceProvider\RouteServiceProvider::class,
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
