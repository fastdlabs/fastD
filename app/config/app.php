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
//        \FastD\Providers\ConfigProvider::class,
        \FastD\Providers\ExceptionProvider::class,
//        \FastD\Providers\LoggerProvider::class,
        \FastD\Providers\RouteProvider::class,
    ],

    /**
     * Exception Handler
     */
    'exception' => [
        'handler' => new class extends \FastD\AppException {
            function handle(Throwable $throwable): \FastD\Http\Stream
            {
                return json([
                    'msg' => $throwable->getMessage(),
                    'code' => $throwable->getCode(),
                    'line' => $throwable->getLine(),
                    'trace' => explode(PHP_EOL, $throwable->getTraceAsString()),
                ])->getBody();
            }
        },
        'options' => [
            'level' => E_ALL
        ]
    ],

    /**
     * Logger Handler
     */
    'logger' => [
        // 日志驱动，系统发生日志读写时触发
    ],
];
