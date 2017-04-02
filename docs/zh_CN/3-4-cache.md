# 缓存

缓存服务提供器与组件主要依赖于: [Symfony Cache](http://symfony.com/doc/current/components/cache.html)，具体操作保持一致。

提供辅助函数: `cache()`，函数返回原始 `Symfony\Component\Cache\Adapter\AbstractAdapter` 对象。目前支持文件缓存、Redis缓存，欢迎有志之士提供各种缓存提供器。

> 3.1 版本开始，支持二维数组配置

配置信息: 

```php
<?php

return [
    'default' => [
        'adapter' => \Symfony\Component\Cache\Adapter\FilesystemAdapter::class,
        'params' => [
        ],
    ]
];
```

函数会根据配置的缓存配置进行读取，`adapter` 适配器是比选参数，`params` 保持为空，在选择用文件缓存的情况下。

#### Redis 缓存

```php
<?php
return [
    'default' => [
        'adapter' => \Symfony\Component\Cache\Adapter\RedisAdapter::class,
        'params' => [
            'dsn' => 'redis://pass@host/dbindex',
        ],
    ]
];
```

仅需要通过配置 `dsn` 选项，redis 适配器会自动处理连接。
 
 #### 操作
 
 ```php
 $cache = cache();
 
 $numProducts = $cache->getItem('stats.num_products');
 
 // assign a value to the item and save it
 $numProducts->set(4711);
 $cache->save($numProducts);
 
 // retrieve the cache item
 $numProducts = $cache->getItem('stats.num_products');
 if (!$numProducts->isHit()) {
     // ... item does not exists in the cache
 }
 // retrieve the value stored by the item
 $total = $numProducts->get();
 
 // remove the cache item
 $cache->deleteItem('stats.num_products');
 ```


下一节: [命令行](3-5-console.md)
