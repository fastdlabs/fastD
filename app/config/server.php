<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

return [
    'url' => 'http://0.0.0.0:9527',
    'server' => \FastD\Swoole\Server\HTTPServer::class,
    'handle' => \FastD\Server\Handle\HttpHandle::class,
    'options' => [
        'pid_file' => __DIR__.'/../runtime/pid/fastd.pid',
        'worker_num' => 1,
    ],
    'processes' => [

    ],
    'listeners' => [
    ],
];
