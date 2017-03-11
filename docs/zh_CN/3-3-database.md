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

### 数据库测试



下一节: [命令行](3-4-cache.md)
