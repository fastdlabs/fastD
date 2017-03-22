<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

return [
    'host' => 'udp://0.0.0.0:9527',
    'class' => \FastD\Servitization\Server\UDPServer::class,
    'options' => [
        'pid_file' => __DIR__ . '/../runtime/pid/' . app()->getName() . '.pid',
        'worker_num' => 10,
        'task_worker_num' => 20,
    ],
    'processes' => [

    ],
    'listeners' => [
        [
            'class' => \FastD\Servitization\Server\TCPServer::class,
            'host' => 'tcp://127.0.0.1:9528',
        ],
        [
            'class' => \FastD\Servitization\Server\MonitorStatusServer::class,
            'host' => 'tcp://127.0.0.1:9529',
        ],
    ],
];