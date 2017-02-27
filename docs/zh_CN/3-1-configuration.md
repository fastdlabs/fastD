# 应用配置

应用配置分为 3 中配置类型

1. 基础配置
2. 服务器配置
3. 自定义配置

基础配置分别由:

1. 路由配置
2. 应用配置

组成，分别是存储基础业务配置和路由访问的地方.

##### 路由配置

路由配置则是具体的路由配置信息，具体请前往: [路由与控制器](2-1-routing-and-controllers.md)

##### 应用配置

应用配置则是整体核心配置的集合，包括时区，环境，日志，服务提供器，中间件等等，可以通过自定义 [服务提供器](3-6-service-provider.md) 来读取具体的配置内容。

具体内容请查看: [app.php](../../tests/config/app.php)

**完整的配置项**

```php
<?php
return [
    /**
     * The application name.
     */
    'name' => 'fast-d',

    /**
     * Run environment
     */
    'env' => env('env'),

    /**
     * Application timezone
     */
    'timezone' => 'PRC',

    /**
     * Application logger path
     */
    'log' => [
        'path' => 'storage',
        'info' => \Monolog\Handler\StreamHandler::class, // 访问日志
        'error' => \Monolog\Handler\StreamHandler::class, // 错误日志
    ],

    /**
     * Bootstrap service.
     */
    'providers' => [
        \FastD\ServiceProvider\DatabaseServiceProvider::class,
        \FastD\ServiceProvider\CacheServiceProvider::class,
    ],

    /**
     * Http middleware
     */
    'middleware' => [
        'basic.auth' => new FastD\BasicAuthenticate\HttpBasicAuthentication([
            'authenticator' => [
                'class' => \FastD\BasicAuthenticate\PhpAuthenticator::class,
                'params' => [
                    'foo' => 'bar'
                ]
            ],
            'response' => [
                'class' => \FastD\Http\JsonResponse::class,
                'data' => [
                    'msg' => 'not allow access',
                    'code' => 401
                ]
            ]
        ])
    ],
];
```

> !! 默认的配置项请不要删除

##### 服务器配置

服务器配置项 listen 是必填的，是 Swoole 服务器监听的地址。其他配置请查看 [Swoole配置](http://wiki.swoole.com/wiki/page/274.html)

**完整的配置**

```php
<?php
return [
    'listen' => 'http://0.0.0.0:9527',
    'class' => \FastD\Servitization\Server\HTTPServer::class,
    'options' => [
        'pid_file' => '',
        'worker_num' => 10,
        'task_worker_num' => 20,
    ],
    'processes' => [
        [
            'class' => \FastD\Servitization\Discovery\Discover::class,
            'scheme' => 'tcp://127.0.0.1:9888',
        ],
    ],
    'servers' => [
        [
            'class' => \FastD\Servitization\Server\TCPServer::class,
            'listen' => 'tcp://127.0.0.1:9528',
        ],
    ],
];
```

##### 自定义配置项

database.php 与 cache.php 是框架默认提供的扩展配置，由 `DatabaseServiceProvider` 与 `CacheServiceProvider` 进行具体处理。

其中 database.php 与 cache.php 虽说是框架默认提供的，但是他们均属于自定义服务提供器之一。

如果需要自定添加或者修改服务提供器，具体请参考: [服务提供器](3-8-service-provider.md)

下一节: [中间件](3-2-middleware.md)
