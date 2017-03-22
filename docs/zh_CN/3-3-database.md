# 数据库

框架模式使用 [medoo](https://github.com/catfan/Medoo) 框架，提供最简便的操作。如果想使用 ORM 的朋友可以尝试添加 [ServiceProvider](3-8-service-provider.md)，作为框架的一个扩充。

### 基础 medoo 使用

ORM 框架

* [doctrine2](https://github.com/doctrine/doctrine2)
* [redbean](https://github.com/gabordemooij/redbean)
* [propel2](https://github.com/propelorm/Propel2)

数据库配置: 

```php
<?php
return [
    'adapter' => 'mysql',
    'name' => 'ci',
    'host' => '127.0.0.1',
    'user' => 'travis',
    'pass' => '',
    'charset' => 'utf8',
    'port' => 3306,
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
$ php bin/console seed:create Demo
```

文件构建在 `database` 目录下，名字自动构建如下: 

```php
<?php

use Phinx\Seed\AbstractSeed;

class Demo extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        
    }
}
```

通过实现 run 方法，添加数据库结构，方便表结构迁移。

```php
<?php

use FastD\Model\Migration;

class Demo extends Migration
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function up()
    {
        $table = $this->table('demo');
        $table->addColumn('user_id', 'integer')
            ->addColumn('created', 'datetime')
            ->create();
    }
}
```

编写完成初步的表结构，运行命令: 

```shell
$ php bin/console seed:run
```

自动构建数据表，但是需要先手动创建数据库。具体操作可参考: [phinx](https://tsy12321.gitbooks.io/phinx-doc/writing-migrations-working-with-tables.html)

下一节: [缓存](3-4-cache.md)
