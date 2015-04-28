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
            'database_host'     => 'localhost',
            'database_port'     => 3306,
            'database_user'     => 'root',
            'database_pwd'      => '123456',
            'database_charset'  => 'utf8',
            'database_name'     => 'test',
            'database_prefix'   => ''
        ],
        'read' => [
            'database_type'     => 'mysql',
            'database_host'     => 'localhost',
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
    // session存储
    'session' => [

    ],
    // 模板引擎
    'template' => [
        'engine' => 'twig',
        'paths' => [
            realpath(__DIR__ . '/../views'),
            realpath(__DIR__ . '/../../src'),
        ],
        'debug' => true,
        'cache' => realpath(__DIR__ . '/../storage/templates'),
        // twig 扩展函数
        'extensions' => [
            'path' => new \Twig_SimpleFunction('path', function ($path, array $parameters = array(), $suffix = false) {
                return Make::url($path, $parameters, $suffix);
            }),
            'asset' => new \Twig_SimpleFunction('asset', function ($name, $host = null, $path = null) {
                return Make::asset($name, $host, $path);
            }),
        ],
    ],
    'assets' => [
        'host' => 'http://baidu.com',
        'path' => 'public'
    ],
    // 错误提示
    'errors' => [

    ],
    // 日志对象
    'logger' => [
        'name' => 'dobee.log',
        'path' => realpath(__DIR__ . '/../storage/logs/' . date('Ymd')),
    ],
];