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

#### 注册命令行

在默认情况下，命令行一般会存储在 `src/Console` 目录中，对于一些特殊情况，例如我需要在扩展包中注册命令行，就需要手动注册命令行了。

框架本身提供两种注册方式，一种是配置文件注册，另外一种是通过在服务提供者 `register` 方法中手动注册。

```php
class FooServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->add('foo', new Foo());
        config()->merge([
            'consoles' => [
                DemoConsole::class
            ]
        ]);
    }
}
```

代码解析: 

在系统中，命令行 application 会执行如下代码，用于注册用户自定义命令，而命令来至于配置，那么只需要我们将命令行对象添加到配置中，即可达成注册的效果.

```php
foreach (config()->get('consoles', []) as $console) {
    $this->add(new $console());
}
```

下一节: [单元测试](3-6-testcase.md)
