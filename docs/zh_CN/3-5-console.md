# 命令行

命令行依赖于 [symfony/console](https://github.com/symfony/console) 具体操作可以查看 [console](http://symfony.com/doc/current/console.html)

版本: `^3.2`

所有命令行文件存放在 `src/Console` 目录中，命令行对象需要继承 `Symfony\Component\Console\Command\Command`, 在启动 Console 控制台对象的时候，程序会自动扫描目录下所有命令行文件，并且进行处理。
 
```php
<?php

namespace Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Demo extends Command
{
    public function configure()
    {
        $this->setName('demo');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('hello world');
    }
}
```

更多请查看: [console](http://symfony.com/doc/current/console.html)

### 命令列表

包含框架内置所有的初始化命令。

##### config:dump

打印配置文件信息

##### controller:create

创建增删改查控制器

##### model:create

创建增删改查模型

##### route:dump

列表打印所有路由

##### seed:create

创建数据库种子文件

##### seed:run

执行数据库种子文件

下一节: [单元测试](3-6-testcase.md)
