<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

/**
 * 应用引导文件
 */
return [
    /**
     * App 运行环境
     */
    'env' => 'dev',

    /**
     * 应用目录, 程序运行后, 会根据 root.path 加载对应的文件
     */
    'app.path' => __DIR__ . '/tests/app',

    /**
     * 入口目录
     */
    'web.path' => __DIR__,

    /**
     * Swoole Server 配置信息。
     */
    'server' => [
        'listen' => 'http://0.0.0.0:9527',
    ],
];