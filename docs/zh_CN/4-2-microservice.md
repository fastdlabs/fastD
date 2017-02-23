# RPC 服务

基于 Swoole 扩展，可以很容易地实现 PHP 之间的网络通信，也可以很好地实现 RPC 服务，推荐学习的 RPC 入门基础框架: 

* [Dora-RPC](https://github.com/xcl3721/Dora-RPC)

先理解一个图 [![RPC](https://github.com/weibocom/motan/wiki/media/14612349319195.jpg)] 

我们再每次实现一个服务时候，都需要将添加的服务马上注册到发现中心，客户端在发起调用的时候，可以由发现中心进行服务发现，通过指定算法负载到指定的后端服务器当中。

其实内部就是一个网络通信的过程，服务器需要主动告诉发现端 "自己" 的存在，发现端就收到后，就可以确定该服务可用。

#### 开启基础服务

框架实现了服务端基础功能，支持 HTTP，TCP 服务多端口监听，服务不建议使用 UDP 形式进行管理。

设置 `config/server.php` 的 `ports` 配置项，添加内置 `TCPServer`，如下:

```php
<?php
return [
    'listen' => 'http://0.0.0.0:9527',
    'options' => [
        'pid_file' => '',
        'worker_num' => 10,
        'task_worker_num' => 20,
    ],
    'ports' => [
        [
            'class' => \FastD\Server\TCPServer::class,
            'listen' => 'tcp://127.0.0.1:9528',
        ],
    ],
];
```

#### 开启服务状态上报

服务器状态上报，需要开启一个监控端 [Discovery](https://github.com/JanHuang/discovery)，接受服务器状态的服务器(可以通过 FastD API 框架进行实现)，然后编写一个上报状态的进程，订立上报机制，对状态进行定时汇报。

开启发现端和监控端，添加状态上报进程。

```php
return [
    'listen' => 'http://0.0.0.0:9527',
    'options' => [
        'pid_file' => '',
        'worker_num' => 10,
        'task_worker_num' => 20,
    ],
    'discovery' => [
        'tcp://127.0.0.1:9888'
    ],
    'monitor' => [
        'tcp://127.0.0.1:9889'
    ],
    'processes' => [
        \FastD\Discovery\Discover::class
    ],
    'ports' => [
        [
            'class' => \FastD\Server\TCPServer::class,
            'listen' => 'tcp://127.0.0.1:9528',
        ],
    ],
];
```

开启 Discover 进程后，进程会以自动在服务启动后自动上报当前服务状态，间隔1s。

若 monitor 也配置后，那么会在接受请求的时候将调用链，参数，服务等信息一等上报到监控端。
