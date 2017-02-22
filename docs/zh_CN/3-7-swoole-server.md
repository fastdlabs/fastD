# Swoole 服务器

Swoole 服务器依赖于 [swoole](https://github.com/JanHuang/swoole) 并且提供灵活优雅的实现方式。

> swoole 必须依赖于 ext-swoole 扩展，并且版本 >= 1.8.5

swoole 服务器的配置文件存放在 `config/server.php` 中，其中 `listen` 选项是必填，`options` 配置项请看 [server 配置选项](http://wiki.swoole.com/wiki/page/274.html)

```php
$ php bin/server {start|status|stop|reload} [-t] {web root 目录，默认使用当前命令执行路径}
```

默认操作方式是 `status`，可用于简单查看进程状态。

> 访问的方式与 FPM 下访问保持一致。开发环境下推荐使用 FPM，生产环境可以尝试用 Swoole 代替 FPM，如果有必要的话

### 守护进程

以守护进程的方式启动，只需要加一个 `--daemon|-d` 选项即可。

```php
$ php bin/server {start|status|stop|reload} -d > /dev/null
```

启动守护进程需要屏蔽输出，屏蔽输出仅仅是屏蔽输出而已，并不会对程序和性能造成影响。

以上即可通过不修改代码，支持 Swoole 操作。

### 文件监听

文件监听需要依赖 [inotify](https://pecl.php.net/package/inotify) 扩展，需要自行安装。

```php
$ php bin/server watch --dir=需要监听的目录
```

当监听的目录发生改变时，服务器会自动重启，推荐在开发模式下启用。

### 多端口监听

服务器支持多端口监听，只需要简单的配置。

```php
return [
    'listen' => 'http://0.0.0.0:9527',
    'options' => [
        'pid_file' => '',
        'worker_num' => 10
    ],
    // 多端口监听
    'ports' => [
        [
            'class' => \FastD\Server\TCPServer::class,
            'listen' => 'tcp://127.0.0.1:9528',
            'options' => [
                
            ],
        ],
    ],
];
```

框架提供一个默认的 TCP 服务，以提供 TCP 调用，可以通过创建发现服务器，监控实现一套 RPC 系统。

每个需要监听的端口需要继承 `FastD\Swoole\Server` 对象，实现内部抽象方法，具体请查看 [examples](https://github.com/JanHuang/swoole/blob/master/examples/multi_port_server.php)

**注意事项**

swoole_http_server和swoole_websocket_server因为是使用继承子类实现的，无法使用listen创建Http/WebSocket服务器。如果服务器的主要功能为RPC，但希望提供一个简单的Web管理界面。

在这样的场景中，可以先创建Http/WebSocket服务器，然后再进行listen监听RPC服务器的端口。

* [Swoole 监听端口](http://wiki.swoole.com/wiki/page/525.html)

### 服务器进程

服务器内置 Process 进程，在启动服务器的时候会自动拉起进程，通过 [Swoole::addProcess](http://wiki.swoole.com/wiki/page/390.html) 实现。

配置依然是 [server.php](../../tests/config/server.php)。

```php
return [
    'listen' => 'http://0.0.0.0:9527',
    'options' => [
        'pid_file' => '',
        'worker_num' => 10
    ],
    'processes' => [
        'class' => \Processor\DemoProcessor::class
    ],
];
```

重写 `FastD\Swoole\Process` 的 `handle` 方法，`handle` 为进程具体执行的事务。示例: [DemoProcessor](../../tests/src/Processor/DemoProcessor.php)

### 服务状态上报与发现

服务器状态上报，需要开启一个监控端，接受服务器状态的服务器(可以通过 FastD API 框架进行实现)，然后编写一个上报状态的进程，订立上报机制，对状态进行定时汇报。

示例编写中...

下一节: [扩展](3-8-extend.md)
