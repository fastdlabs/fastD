# 路由与控制器

路由的提供来源于 [routing](https://github.com/JanHuang/routing) 组件，提供高性能的路由配置和解析处理，良好地支持 RESTful。

路由配置文件存放于 `config/routes.php` 文件中。

### 路由配置

在路由回调处理中，控制器不需要编写命名空间，默认在控制器前追加 `Http\Controoler` 命名空间。

##### 方法路由:
 
```php
route()->get('/', 'IndexController@sayHello');
``` 

```php
route()->post('/', 'IndexController@sayHello');
```

支持 `get, post, put, head, delete` 方法

##### 路由组

```php
route()->group('/v1', function () {
    route()->get('/', 'IndexController@sayHello');
});
```

以上路由会在用户访问 `/v1/` 或者 `/v1` 时候进行回调处理

##### 路由模糊匹配

```php
route()->get("/foo/*", "IndexController@sayHello");
```

此模式会将 /foo 开头的 `[\/_a-zA-Z0-9-]+` 匹配地址到控制器当中，通过 `fuzzy_path` 参数进行获取匹配到的地址。

```php
$request->getAttribute('fuzzy_path');
```

### 控制器

路由配置不支持匿名函数回调，因此在核心处理中屏蔽了该功能，用户保持配置文件的清洁与独立，建议开发者使用控制器回调的方式进行处理。

> 控制器无需继承任何对象，方法均有 [辅助函数](3-5-helpers.md) 提供，控制器目前存放于 Http 目录中，3.1 版本后将统一控制器入口，同时为TCP、HTTP提供服务

```php
namespace Http\Controller;


class IndexController
{
    public function sayHello()
    {
        return json([
            'foo' => 'bar'
        ]);
    }
}
```

仔细的朋友不难发现，其实此处的控制器就是一个 "中间件" 的回调处理，如果在 [中间件](3-2-middleware.md) 中逻辑处理错误，是不会进入到控制器中的。

中间件的实现依赖于 [Middleware](https://github.com/JanHuang/middleware) 组件。

如果该路由是动态路由，则参数需要通过 `ServerRequestInterface` 对象进行访问。

```php
route()->get('/hello/{name}', 'IndexController@sayHello');
```

```php
namespace Http\Controller;


use FastD\Http\ServerRequest;

class IndexController
{
    public function sayHello(ServerRequest $request)
    {
        return json([
            'name' => $request->getAttribute('name')
        ]);
    }
}
```

如此类推。

### 数据库模型

框架提供简单的数据库模型，暂时不提供 ORM 等复杂操作，因为本身定位不在此处，如果想要使用 ORM 等操作，可以通过自定义 [服务提供器](3-6-service-provider.md) 来扩展。

模型没有强制要求继承 `FastD\Model\Model`，但是在每个模型初始化的时候，会默认在构造方法中注入 `medoo` 对象，分配在 `db` 属性当中。

```php
$model = model('demo');
```

模型放置在 Model 目录中，如果没有该目录，需要通过手动创建目录，通过使用 `model` 方法的时候，会自动将命名空间拼接到模型名之前，并且模型名不需要带上 `Model` 字样，如: `model('demo'')` 等于 `new Model\DemoModel`。

下一节: [请求](2-2-request-handling.md)
