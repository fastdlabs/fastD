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
     * App 需要注册的业务应用组件
     *
     * 需要实例化组件引导对象。 如: new WelcomeBundle
     *
     * Bundle 引导文件主要声明, 声明路径, 控制器, 独立配置等信息路径
     */
    'bundles' => [
        new \WelcomeBundle\WelcomeBundle(),
    ],

    /**
     * App 运行环境
     */
    'env' => 'prod',

    /**
     * 源码目录, 程序运行后, 会根据 root.path 加载对应的文件
     */
    'src.path' => __DIR__ . '/tests/app',

    /**
     * 入口目录
     */
    'web.path' => __DIR__ . '/web',

    /**
     * 项目公共配置
     */
    'config' => [

    ],

    /**
     * Swoole Server 配置信息。
     */
    'server' => [
        'listen' => 'http://0.0.0.0:9527',
    ],
];