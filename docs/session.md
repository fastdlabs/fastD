# Session

FastD 的 Session 由 `fastd/http` 的 PSR-15 中间件和 `FastD\ServiceProvider\SessionServiceProvider` 提供。

## Handler 使用类名

配置中的 `handler` 推荐直接使用类名，避免字符串拼写错误：

```php
<?php

use FastD\Http\Session\Handler\NativeFileSessionHandler;

return [
    'enabled' => true,
    'handler' => NativeFileSessionHandler::class,
];
```

Swoole 共享内存：

```php
use FastD\Http\Session\Handler\SwooleTableSessionHandler;

return [
    'enabled' => true,
    'handler' => SwooleTableSessionHandler::class,
    'swoole_table' => [
        'size' => 2048,
        'max_data_size' => 4096,
    ],
];
```

## Redis

```php
<?php

use FastD\Http\Session\Handler\RedisSessionHandler;

return [
    'enabled' => true,
    'handler' => RedisSessionHandler::class,
    'ttl' => 1440,
    'redis' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => null,
        'database' => 0,
        'timeout' => 1.0,
        'prefix' => 'fastd:session:',
    ],
];
```

要求：

- `ext-redis`
- `fastd/http >= 8.2`（`RedisSessionHandler`）

Redis 连接由 `SessionServiceProvider` 在初始化时建立：

- 读取 `redis.host` / `port` / `password` / `database` / `timeout`
- 创建 `\Redis` 连接后注入 `RedisSessionHandler`

`auto` 仍可继续使用：

```php
'handler' => 'auto',
```

- Swoole runtime：Swoole Table
- CGI runtime：Native File

也兼容旧的 `swoole_table` / `native_file` / `redis` 字符串别名。
