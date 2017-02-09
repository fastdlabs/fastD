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
        'worker_num' => 10
    ],
    'processes' => [
        \Processor\DemoProcessor::class
    ],
    'ports' => [
        [
            'class' => \Port\DemoPort::class,
            'listen' => 'tcp://127.0.0.1:9528',
            'options' => [

            ],
        ],
    ],
];