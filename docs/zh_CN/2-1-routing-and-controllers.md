# 路由与控制器

路由的提供来源于 [routing](https://github.com/JanHuang/routing) 组件，提供高性能的路由配置和解析处理，良好地支持 RESTful，支持模糊匹配。

### 路由配置

在路由回调处理中，控制器不需要编写命名空间，默认在控制器前追加 `Controller` 命名空间。

> 3.1 版本控制器已经迁移到 `Controller` 目录，3.0 版本存储在 `Http/Controller` 目录

#### 方法路由
 
```php
route()->get('/', 'IndexController@sayHello');
``` 

```php
route()->post('/', 'IndexController@sayHello');
```

支持 `get, post, put, head, delete` 方法。添加路由名，可以更加方便在 [TCPServer](3-9-swoole-server.md) 中调用

#### 路由组

```php
route()->group('/v1', function () {
    route()->get('/', 'IndexController@sayHello');
});
```

以上路由会在用户访问 `/v1/` 或者 `/v1` 时候进行回调处理。

#### 模糊匹配

```php
route()->get("/foo/*", "IndexController@sayHello");
```

此模式会将 /foo 开头的 `[\/_a-zA-Z0-9-]+` 匹配地址到控制器当中，通过 `fuzzy_path` 参数进行获取匹配到的地址。

```php
$request->getAttribute('fuzzy_path');
```

### 中间件

路由是整个框架最核心的功能之一，最后执行会根据路由地址操作最终的回调处理，而这个回调处理本身就是一个中间件处理模块之一。

使用中间件前，需要先配置可用中间件列表，通过 `config/app.php` 配置文件进行配置

```php
<?php

return [
    // some code...
    /**
     * Http middleware
     */
    'middleware' => [
        'basic.auth' => new FastD\BasicAuthenticate\HttpBasicAuthentication([
            'authenticator' => [
                'class' => \FastD\BasicAuthenticate\PhpAuthenticator::class,
                'params' => [
                    'foo' => 'bar'
                ]
            ],
            'response' => [
                'class' => \FastD\Http\JsonResponse::class,
                'data' => [
                    'msg' => 'not allow access',
                    'code' => 401
                ]
            ]
        ])
    ],
];
```

健名 `basic.auth` 即是中间件名字，可以通过 

```php
route()->post('/', 'IndexController@sayHello')->withMiddleware('basic.auth');
```

进行配置。每当程序调用 `/` 地址的时候，会先经过配置的中间件。

##### 路由组中间件

```php
route()->group(['prefix' => '/v1', 'middleware' => 'demo'], function () {
    route()->get('/', 'IndexController@sayHello');
});
```

路由组支持全局设置中间件，可以子路由可以统一设置中间。子路由中会默认继承路由组中的中间级，如果在路由组中继续定义中间件，会继续追加到指定路由中。

### 控制器

路由配置不支持匿名函数回调，因此在核心处理中屏蔽了该功能，用户保持配置文件的清洁与独立，建议开发者使用控制器回调的方式进行处理。

**控制器目前存放于 Http 目录中，3.1 版本后将统一控制器入口，同时为TCP、HTTP提供服务, 去除 Http 目录，保留 Controller 目录，其他结构不变**

> 控制器无需继承任何对象，方法均有 [辅助函数](3-5-helpers.md) 提供

```php
namespace Controller;


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

如上述，此处的控制器就是一个 "中间件" 的回调处理，如果在 [中间件](3-2-middleware.md) 中逻辑处理错误，是不会进入到控制器中的。

中间件的实现依赖于 [Middleware](https://github.com/JanHuang/middleware) 组件。

如果该路由是动态路由，则参数需要通过 `ServerRequestInterface` 对象进行访问。

##### 示例

**config/routes.php**

```php
route()->get('/hello/{name}', 'IndexController@sayHello');
```

**Controller\IndexController**

```php
namespace Controller;


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

下一节: [请求](2-2-request-handling.md)
