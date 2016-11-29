<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

return [
    'request' => \FastD\Http\ServerRequest::class,
    'response' => \FastD\Http\JsonResponse::class,
    'log' => Monolog\Logger::class,
    'session' => \FastD\Session\Session::class,
    'database' => \FastD\Database\Fdb::class,
    'store' => '',
    'storage' => '',
];