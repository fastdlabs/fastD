# Upgrading FastD

## 8.1.x → 8.2.x

Swoole 配置结构已调整，且不保留旧配置兼容。

### 配置变更

| 8.1 | 8.2 |
|---|---|
| `listen` | `servers` |
| `listen[].worker` | `servers.<name>.listener` |
| `listener` | `listeners` |
| `setting` | `options` |

### 旧配置

```php
return [
    'listen' => [
        [
            'host' => '127.0.0.1',
            'port' => 9527,
            'worker' => \FastD\Listener\Server\SwHttpListener::class,
        ],
    ],
    'listener' => [
        \FastD\Swoole\Listener\Server\WorkerListener::class,
    ],
    'setting' => [
        'log_level' => 5,
    ],
];
```

### 新配置

```php
return [
    'servers' => [
        'http' => [
            'host' => '127.0.0.1',
            'port' => 9527,
            'listener' => \FastD\Listener\Server\SwHttpListener::class,
        ],
    ],
    'listeners' => [
        \FastD\Swoole\Listener\Server\WorkerListener::class,
    ],
    'options' => [
        'log_level' => 5,
    ],
];
```

## 兼容矩阵

| fastD | dobee | dobee-demo |
|---|---|---|
| 8.2.x | 1.4.x | 1.4.x |
| 8.1.x | 1.3.5 | 1.3.3 |
