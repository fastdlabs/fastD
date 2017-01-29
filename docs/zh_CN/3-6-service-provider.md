# 服务提供器

服务提供器提供了自定义扩展和处理的入口，实现依赖于 [container](https://github.com/JanHuang/container)

每个提供器都需要实现 `FastD\Container\ServiceProviderInterface` 接口，实现 `register` 方法并处理服务提供。

如数据库服务提供器

```php
namespace FastD\ServiceProvider;


use FastD\Config\ConfigLoader;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use medoo;

class DatabaseServiceProvider implements ServiceProviderInterface
{
    protected $db;

    public function register(Container $container)
    {
        $config = ConfigLoader::loadPhp(app()->getAppPath() . '/config/database.php');

        $container->add('database', function () use ($config) {
            if (null === $this->db) {
                $this->db = new medoo($config);
            }
            return $this->db;
        });

        unset($config);
    }
}
```

通过 `register` 方法，将服务注入到 `container` 容器当中，提供给全局使用，因为整个 Application 就是一个 容器。具体可查看 [Application.php](../../src/Application.php)

下一节: [Swoole服务器](3-7-swoole-server.md)
