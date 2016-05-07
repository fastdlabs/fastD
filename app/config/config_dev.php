<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/28
 * Time: 上午11:52
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

return [
    // 数据库配置
    'database' => [
        'write' => [
            'database_type'     => 'mysql',
            'database_host'     => '127.0.0.1',
            'database_port'     => 3306,
            'database_user'     => 'root',
            'database_pwd'      => '123456',
            'database_charset'  => 'utf8',
            'database_name'     => 'test',
            'database_prefix'   => ''
        ],
        'read' => [
            'database_type'     => 'mysql',
            'database_host'     => '127.0.0.1',
            'database_port'     => 3306,
            'database_user'     => 'root',
            'database_pwd'      => '123456',
            'database_charset'  => 'utf8',
            'database_name'     => 'test',
            'database_prefix'   => ''
        ],
    ],
    // 存储配置
    'storage' => [
        'write' => [
            'type' => 'redis',
            'host' => '11.11.11.11',
            'port' => 6379
        ],
    ],
    'dynamic' => [
        'path' => '%root.path%',
        'custom' => '%name%'
    ],
];