<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/30
 * Time: 下午2:25
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */
return [
    // session存储 暂不可用
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
        'global' => [
            'request'   => Make::request(),
            'app'       => Make::container('kernel'),
            'make'      => Make::getMakeTool(),
        ],
    ],

    // 错误提示
    'errors' => [
        '404' => 'errors/404.html.twig',
    ],
    // 日志对象
    'logger' => [
        'name' => 'dobee.log',
        'path' => realpath(__DIR__ . '/../storage/logs/' . date('Ymd')),
    ],
];