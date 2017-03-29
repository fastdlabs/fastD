# 升级指南

1. 调整配置文件 `config/cache.php` 和 `config/database.php` 一维数组变成二维数组

##### cache.php

```php
<?php
return [
    'default' => [
        'adapter' => \Symfony\Component\Cache\Adapter\FilesystemAdapter::class,
        'params' => [
        ],
    ],
];
```

##### database.php

```php
<?php
return [
    'default' => [
        'adapter' => 'mysql',
        'name' => 'ci',
        'host' => '127.0.0.1',
        'user' => 'travis',
        'pass' => '',
        'charset' => 'utf8',
        'port' => 3306,
    ]
];
```

2. 调整内置服务器命名空间及对象名, 

3. 将目录 `Http/Controller` 改为 `Controller`，命名空间也改为 `Controller`
