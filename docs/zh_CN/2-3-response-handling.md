# 响应处理

API 返回均是响应 `json`, 因为主要单独针对 API 场景, 如果功能无法满足业务需要，可以通过自定义 [扩展](3-8-extend.md) 来实现业务目的。

```php
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

下一节: [授权](2-4-authorization.md)