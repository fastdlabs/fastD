# Swoole进程管理

操作进程和操作命令行一样简单，由于进程不依赖任何环境。当然，如果你使用到系统内置函数或者对象，那还是需要对依赖进行处理的

#### 创建进程处理

值得注意的是，此处进程与 swoole 内置进程不同，他本身与 server 是独立运行的，因此在创建的时候无需依赖 server。

> 进程支持Server中使用和单独运行。
 
创建进程文件:

```php
namespace Processor;


use FastD\Swoole\Process;
use swoole_process;

class DemoProcessor extends Process
{
    public function handle(swoole_process $swoole_process)
    {
        timer_tick(1000, function ($id) {
            static $index = 0;
            $index++;
            echo $index . PHP_EOL;
            if ($index === 3) {
                timer_clear($id);
            }
        });
    }
}
```

> 进程必须继承 `FastD\Swoole\Process` 对象，实现: `handle` 方法，否则无法运行

#### 启动进程

```
php bin/console process:create {ProcessName} [--name] [--daemon|-d]
```

命令会保存进程的文件信息，如: pid，保存目录在 `runtime/process` 目录中

下一节: [连接池](3-11-connection-pool.md)