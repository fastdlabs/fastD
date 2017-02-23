<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

include __DIR__ . '/bootstrap.php';

$client = new \FastD\Client\TCP('tcp://127.0.0.1:9528/');

$client
    ->connect(function ($server) {
        $server->send(json_encode([
            'cmd' => '/foo/jan',
            'method' => 'get'
        ]));
    })
    ->receive(function ($server, $data) {
        echo $data . PHP_EOL;
        $server->close();
    })
    ->resolve()
;