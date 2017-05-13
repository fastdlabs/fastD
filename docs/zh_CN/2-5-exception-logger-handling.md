# 异常与日志处理

框架中提供基础错误处理，可以集中式处理各种异常。日志存储在 `runtime/logs` 中，可以通过业务需求，讲日志集中发送到一个日志服务器中，FastD 正在研发解决方案，敬请期待: [LogViewer](4-5-fastd-log-viewer.md)

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

当我们框架执行出现异常时候，并且不希望将敏感信息输出到客户端，那么可以通过 `config/app.php` 进行配置屏蔽，如: 

```php
<?php

return [
    // ...
    'exception' => [
        'response' => function (Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
                // 'file' => $e->getFile(),
                // 'line' => $e->getLine(),
                // 'trace' => explode("\n", $e->getTraceAsString()),
            ];
        },
    ],
    // ...
];
```

实现原理非常简单: 

```php
<?php

// ...
$handle = config()->get('exception.handle');
$response = json($handle($e), $statusCode);
// ...
```

### 日志处理

日志服务依赖于 [monolog](https://github.com/Seldaek/monolog)，当应用程序发生异常时，程序会将异常信息记录在日志中。

> 3.1 开始日志配置增强至数组处理，支持自定义日志格式和传输

配置: 

```php
<?php
return [
    'log' => [
        [\Monolog\Handler\StreamHandler::class, 'error.log', \Monolog\Logger::ERROR]
    ],
];
```

数组接受 4 个参数，分别解析成: handler, log file, level, formatter

实现原理: 

```php
<?php
$logger = new \Monolog\Logger(app()->getName());
list($handle, $name, $level, $format) = array_pad([\Monolog\Handler\StreamHandler::class, 'error.log', \Monolog\Logger::ERROR], 4, null);

if (is_string($handle)) {
    $handle = new $handle($path.'/'.$name, $level);
}
null !== $format && $handle->setFormatter(is_string($format) ? new \Monolog\Formatter\LineFormatter($format) : $format);
$logger->pushHandler($handle);
```

### 集中式日志处理

当我们应用分布过多的时候，就需要考虑如何将日志集中管理起来，方便排查，分析。如果利用 Swoole 即将是一件容易实现的事情。启动我们的日志服务器，然后将每个应用日志都投递到日志服务器中。

应用中仅需要提交数据，数据处理就可以在日志服务器中完成。monolog 中可以使用 [monolog-reader](https://github.com/RunnerLee/monolog-reader) 进行解析。

```php
<?php
return [
    'log' => [
        [new \Monolog\Handler\SocketHandler('schema://host:port'), 'error.log', \Monolog\Logger::ERROR]
    ],
];
```

服务器接收提交信息后，对日志进行解析，批量入库，然后对应做响应的处理即可。

下一节: [应用配置](3-1-configuration.md)
