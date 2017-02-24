<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

return [
    'listen' => 'http://0.0.0.0:9527',
    'options' => [
        'pid_file' => '',
        'worker_num' => 10,
        'task_worker_num' => 20,
    ],
    'discovery' => [
        'tcp://127.0.0.1:9888'
    ],
    'monitor' => [
        'tcp://127.0.0.1:9889'
    ],
    'processes' => [
        \FastD\Servitization\Discovery\Discover::class
    ],
    'ports' => [
        [
            'class' => \FastD\Servitization\Server\TCPServer::class,
            'listen' => 'tcp://127.0.0.1:9528',
        ],
    ],
];