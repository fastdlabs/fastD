# 异常与日志处理

框架中提供基础错误处理，没有实现 `set_error_handle` 也不建议，因为 Swoole 的特殊性，没有全局注册处理。可以集中式处理各种异常。日志存储在 `runtime/logs` 中。

### 异常

框架中的异常会通过 `json` 的形式返回到客户端，输出具体的错误信息

```
HTTP 404 Not Found
```

```json
{
    "msg": "Route \"/d\" is not found.",
    "code": 404,
    "file": "../RouteCollection.php",
    "line": 273,
    "trace": [
    ]
}
```

### 日志处理

日志服务依赖于 [monolog](https://github.com/Seldaek/monolog)，当应用程序发生异常时，程序会将异常信息记录在日志中。

配置: 

```php
<?php
return [
    'log' => [
        'error' => \Monolog\Handler\StreamHandler::class, // 错误日志
    ],
];
```

### 集中式日志处理

当我们应用分布过多的时候，就需要考虑如何将日志集中管理起来，方便排查，分析。如果利用 Swoole 即将是一件容易实现的事情。启动我们的日志服务器，然后将每个应用日志都投递到日志服务器中。

应用中仅需要提交数据，数据处理就可以在日志服务器中完成。monolog 中可以使用 [monolog-reader](https://github.com/RunnerLee/monolog-reader) 进行解析。

```php
<?php
return [
    'log' => [
        'error' => new \Monolog\Handler\SocketHandler('schema://host:port'),
    ],
];
```

服务器接收提交信息后，对日志进行解析，批量入库，然后对应做响应的处理即可。

下一节: [应用配置](3-1-configuration.md)
