# 中间件

中间件的原理其实是一个多层嵌套的匿名函数，由一开始调用的函数开始，一直往下调用，直到后面已经没有回调的时候，返回给客户端。

这里面的中间件也是这样的原理，当中间件处理完成后，最后才到路由中的回调，中间件依赖于 [middleware](https://github.com/JanHuang/middleware) 组件，支持 PSR15。

中间件推荐存放在 `src/Middleware` 目录中，每个中间件必须继承 `FastD\Middleware\Middleware` 对象，实现 `handle` 方法。

```php
namespace FastD\Auth;

use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BasicAuth extends Middleware
{
    /**
     * @param ServerRequestInterface $serverRequest
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $serverRequest, DelegateInterface $delegate)
    {
        if (/* logic */ true) {
            $delegate($serverRequest);
        }
        
        return 'hello';
    }
}
```

中间件中，如果返回的结果是一个字符串，则会默认转化成 `Psr\Http\Message\ResponseInterface` 对象，由中间件调度器进行封装。

因此如果想在中间件中返回不同的格式，那必须返回一个 `Psr\Http\Message\ResponseInterface` 对象，可自定义。

下一节: [命令行](3-3-console.md)
