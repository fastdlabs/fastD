# 响应处理

API 返回均是响应 `json`, 因为主要单独针对 API 场景, 如果功能无法满足业务需要，可以通过自定义 [扩展](3-8-extend.md) 来实现业务目的，但必须返回 `Psr\Http\Message\ResponseInterface` 抽象接口类。

```php
<?php

namespace Controller;


use FastD\Http\ServerRequest;

class IndexController
{
    public function sayHello(ServerRequest $request)
    {
        return json([
            'foo' => 'bar'
        ]);
    }
}
```

json 是框架提供的辅助函数，可以通过 `helpers.php` 进行查阅。

响应对象返回给 Application 后，Application 会自动将 Response 进行包装，输出 Header，Body，如果是 TCP 服务，则会输出具体 Body。

如果想要自定义输出格式，你需要实现自己的服务器。具体实现可以参考内置的服务器: [HTTP](../../src/Servitization/Server/HTTPServer.php), [TCP](../../src/Servitization/Server/TCPServer.php)

服务器基于 Swoole 扩展实现，具体可以前往: [Swoole服务器](3-9-swoole-server.md)

#### 中断

在应用开发过程中，除了有正常的响应外，更多的是需要提供完善的异常处理。异常处理我们提供 `abort` 函数，立即中断并返回数据。

```php
abort(400);
```

程序中自动抛出异常，有系统捕获，减少开发人员判断的工作。

#### 响应编码

除了日常可理解的相应数据外，3.2 还提供 binary 编码响应。

```php
function xxx ()
{
    return binary([
        'foo' => 'bar'
    ]);
}
```

下一节: [授权](2-4-authorization.md)