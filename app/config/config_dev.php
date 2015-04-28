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
            'path' => new \Twig_SimpleFunction('path', function ($path, array $parameters = array()) {
                return Application::create()->getContainer()->get('kernel.routing')->generateUrl($path, $parameters);
            }),
            'asset' => new \Twig_SimpleFunction('asset', function ($name, $host = null, $path = null) {

                $app = Application::create();
                try {
                    $host = $app->getContainer()->get('kernel.config')->getParameters('assets.host');
                } catch (\InvalidArgumentException $e) {
                    $host = $app->getContainer()->get('kernel.request')->getHttpAndHost();
                }

                try {
                    $path = $app->getContainer()->get('kernel.config')->getParameters('assets.path');
                } catch (\InvalidArgumentException $e) {
                    $path = $app->getContainer()->get('kernel.request')->getBaseUrl();

                    if ('' != pathinfo($path, PATHINFO_EXTENSION)) {
                        $path = pathinfo($path, PATHINFO_DIRNAME);
                    }
                }

                return $host . str_replace('//', '/', $path . '/' . $name);
            })
        ],
    ],
    // 错误提示
    'errors' => [

    ],
    // 日志对象
    'logger' => [

    ],
];