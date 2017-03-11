# 请求处理

Http 请求处理来源于 [Http](https://github.com/JanHuang/http) 组件，由其提供强大的 Http 解析预处理，支持 Swoole.

当用户发起一个 Http 请求的时候，Http 组件会将请求封装成一个 ServerRequestInterface 实现类，实现 PSR7 标准，并且将对象传递到控制器中。

> 由于 Http 解析是通过 parse_url 进行解析的，因此您需要配置好你的虚拟域名(virtual-host)进行访问，否则会提示 route 404 not found

```php
namespace Controller;


use FastD\Http\ServerRequest;

class IndexController
{
    public function sayHello(ServerRequest $serverRequest)
    {
        return json($serverRequest->getQueryParams());
    }
}
```

由于 Http 组件实现 PSR7，所以用法是保持 PSR7 一致，操作可以根据 [Http](https://github.com/JanHuang/http) 进行查看

下一节: [响应处理](2-3-response-handling.md)