<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

return [
    'default' => [
        'adapter' => \Symfony\Component\Cache\Adapter\FilesystemAdapter::class,
        'params' => [
        ],
    ],
    'redis' => [
        'adapter' => \Symfony\Component\Cache\Adapter\RedisAdapter::class,
        'params' => [
            'dsn' => 'redis://192.168.199.88'
        ],
    ],
];