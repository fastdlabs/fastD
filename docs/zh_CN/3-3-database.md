# 数据库

框架模式使用 [medoo](https://github.com/catfan/Medoo) 框架，提供最简便的操作。如果想使用 ORM 的朋友可以尝试添加 [ServiceProvider](3-8-service-provider.md)，作为框架的一个扩充。

> 3.1 版本开始，一维数组结构改为二维数组配置. 数据库操作方面会考虑将 medoo 改为可选扩展，并且会考虑使用其他数据库操作进行替换。ORM 是不会内置到框架中，可能会使用一个扩展进行补充。

### 基础 medoo 使用

ORM 框架

* [doctrine2](https://github.com/doctrine/doctrine2)
* [redbean](https://github.com/gabordemooij/redbean)
* [propel2](https://github.com/propelorm/Propel2)

数据库配置: 

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

框架提供辅助函数: `database()`, 函数返回一个 `Medoo\Medoo` 对象。提供最原始的操作，详细 `Medoo` 操作文档: [Medoo Doc](http://medoo.in/doc).

### 数据库模型

框架提供简单的数据库模型，暂时不提供 ORM 等复杂操作，因为本身定位不在此处，如果想要使用 ORM 等操作，可以通过自定义 [服务提供器](3-8-service-provider.md) 来扩展。

模型没有强制要求继承 `FastD\Model\Model`，但是在每个模型初始化的时候，会默认在构造方法中注入 `medoo` 对象，分配在 `db` 属性当中。

```php
$model = model('demo');
```

模型放置在 Model 目录中，如果没有该目录，需要通过手动创建目录，通过使用 `model` 方法的时候，会自动将命名空间拼接到模型名之前，并且模型名不需要带上 `Model` 字样，如: `model('demo'')` 等于 `new Model\DemoModel`。

### 数据表结构

从 3.1 版本开始支持构建简单的数据表模型，通过简单命令构建基础的数据表模型。

```shell
$ php bin/console seed:create Hello
```

文件构建在 `database` 目录下，名字自动构建如下: 

```php
<?php

use FastD\Model\Migration;
use Phinx\Db\Table;

class Hello extends Migration
{
    /**
     * Set up database table schema
     */
    public function setUp()
    {
        // create table
        $table = $this->table('demo');
        $table->addColumn('user_id', 'integer')
            ->addColumn('created', 'datetime')
            ->create();
    }
    
    public function dataSet(Table $table) {
        
    }
}
```

通过实现 setUp 方法，添加数据库结构，方便表结构迁移。

编写完成初步的表结构，运行命令: 

```shell
$ php bin/console seed:run
```

自动构建数据表，但是需要先手动创建数据库。具体操作可参考: [phinx](https://tsy12321.gitbooks.io/phinx-doc/writing-migrations-working-with-tables.html)

### 连接池

当实现 Swoole 服务的时候，数据库会启动一个连接池，在 `onWorkerStart` 启动时候连接数据库，并且在操作时候，检查连接是否断开，实现断线重连机制，断线重连注意事项: [MySQL断线重连](https://wiki.swoole.com/wiki/page/350.html)

实现原理: 

框架提供 `PoolInterface` 接口类，实现 `initPool` 方法。

```php
<?php

namespace FastD\Pool;

use FastD\Model\Database;

/**
 * Class DatabasePool.
 */
class DatabasePool implements PoolInterface
{
    /**
     * @var Database[]
     */
    protected $connections = [];

    /**
     * @var array
     */
    protected $config;

    /**
     * Database constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $key
     *
     * @return Database
     */
    public function getConnection($key)
    {
        if (!isset($this->connections[$key])) {
            if (!isset($this->config[$key])) {
                throw new \LogicException(sprintf('No set %s database', $key));
            }
            $config = $this->config[$key];
            $this->connections[$key] = new Database(
                [
                    'database_type' => isset($config['adapter']) ? $config['adapter'] : 'mysql',
                    'database_name' => $config['name'],
                    'server' => $config['host'],
                    'username' => $config['user'],
                    'password' => $config['pass'],
                    'charset' => isset($config['charset']) ? $config['charset'] : 'utf8',
                    'port' => isset($config['port']) ? $config['port'] : 3306,
                    'prefix' => isset($config['prefix']) ? $config['prefix'] : '',
                ]
            );
        }

        return $this->connections[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function initPool()
    {
        foreach ($this->config as $name => $config) {
            $this->getConnection($name);
        }
    }
}
```

```php
<?php
// ...
public function onWorkerStart()
{
    foreach (app() as $service) {
        if ($service instanceof FastD\Pool\PoolInterface) {
            $service->initPool();
        }
    }
}
// ...
```

下一节: [缓存](3-4-cache.md)
