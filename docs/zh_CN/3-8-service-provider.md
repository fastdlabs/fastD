# 服务提供器

服务提供器提供了自定义扩展和处理的入口，实现依赖于 [container](https://github.com/JanHuang/container)

每个提供器都需要实现 `FastD\Container\ServiceProviderInterface` 接口，实现 `register` 方法并处理服务提供。

如数据库服务提供器

```php
<?php

namespace FastD\ServiceProvider;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Pool\DatabasePool;

/**
 * Class DatabaseServiceProvider.
 */
class DatabaseServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $config = config()->get('database', []);

        $container->add('database', new DatabasePool($config));

        unset($config);
    }
}
```

通过 `register` 方法，将服务注入到 `container` 容器当中，提供给全局使用，因为整个 Application 就是一个 容器。具体可查看 [Application.php](../../src/Application.php)

最终将新增的服务提供器通过 `Class::class` 的方式添加到应用配置的 services 配置项即可。

整体应用都是基于 "容器" 而构成，如果你对容器的概念还不够熟悉的话，可以去参考: [Pimple](https://github.com/silexphp/Pimple), [PHP-DI](https://github.com/PHP-DI/PHP-DI), [container](https://github.com/JanHuang/container)

若果掌握了解更多容器相关知识，相信可以很好地使用该框架。

如果需要尝试添加或者修改服务提供器，可以参考 [DatabaseServiceProvider](../../src/ServiceProvider/DatabaseServiceProvider.php), [database.php](../../tests/app/default/config/database.php), [app.php](../../tests/app/default/config/app.php)

下一节: [Swoole服务器](3-9-swoole-server.md)
