# 授权

授权隶属于中间件的其中一个单元，由中间件调度器进行分发处理。可以自定义实现自己的授权功能。

中间件实现: [中间件](3-2-middleware.md)

已完成的中间件，通过应用配置文件的 `middleware` 配置项进行配置，可以进行多维数据进行嵌套，最终通过 `withMiddleware` 获取 `withAddMiddleware` 进行分配。

授权认证主要依赖于: [basic-auth](https://github.com/JanHuang/basic-authenticate) 组件

应用配置: 

```php
return [
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

认证授权初始化需要接收用户提供器，否则无法判断来源用户是否合法，最后，会通过查询用户合法性来校验，支持 PDO 处理。

路由配置: 

```php
route()->get("/", "IndexController@sayHello")->withAddMiddleware('basic.auth');
```

下一节: [异常处理](2-5-exception-handling.md)
