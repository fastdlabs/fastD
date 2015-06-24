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
    // 模板引擎
    'template' => [
        'engine' => 'twig',
        'paths' => [
            __DIR__ . '/../views',
            __DIR__ . '/../../src',
        ],
        'debug' => true,
        'cache' => __DIR__ . '/../storage/templates',
        // twig 扩展函数
        'global' => [
            'app'       => Make::app(),
            'make'      => Make::getMakeTool(),
        ],
    ],

    // 日志对象
    'logger' => [
        'access' => '%root.path%/storage/logs/%date%/fastd.log',
        'error' => '%root.path%/storage/logs/%date%/error.log',
    ],
];