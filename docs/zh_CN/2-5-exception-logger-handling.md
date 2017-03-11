# 异常与日志处理

框架中提供基础错误处理，没有实现 `set_error_handle` 也不建议，因为 Swoole 的特殊性，没有全局注册处理。可以集中式处理各种异常。

### 异常

框架中的异常会通过 `json` 的形式返回到客户端，输出具体的错误信息

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

日志服务依赖于 [monolog](https://github.com/Seldaek/monolog)

日志在默认开发环境和测试环境中是不会创建的，当环境运行在生产环境上，则会自动创建访问日志，错误日志，日志可以根据业务日志处理进行传输。

配置: 

```php
return [
    'log' => [
        'info' => \Monolog\Handler\StreamHandler::class, // 访问日志
        'error' => \Monolog\Handler\StreamHandler::class, // 错误日志
    ],
];
```

当日志存储在本地的时候，填充 path 选项，路径会自动在应用目录下进行拼接，所以 path 是更好地区分目录，而这个时候 info、error 两者选项是可选的，默认使用 `\Monolog\Handler\StreamHandler` 进行存储。

当需要修改 StreamHandler 的时候，则直接映射 StreamHandler 实现类的类名即可。

当如果需要其他存储方式的时候，则直接实例化 logger 对象即可。

下一节: [应用配置](2-6-docuemnt.md)
