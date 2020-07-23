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
        \FastD\Service\RouteProvider::class,
    ],

    /**
     * Exception Handler
     */
    'exception' => [
        // 异常格式，出现异常时候，系统会按照自定义格式进行处理
        'handle' => '',
        'level' => E_ALL
    ],

    /**
     * Logger Handler
     */
    'logger' => [
        // 日志驱动，系统发生日志读写时触发
        'formatter' => [],
    ],
];
