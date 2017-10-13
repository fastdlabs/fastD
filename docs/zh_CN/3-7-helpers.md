# 辅助函数

辅助函数目前暂时没有提供自定的模板，可以通过 composer.json 中的 autoload 进行自定义扩展。

[PSR规范官网](http://www.php-fig.org/)

#### app(): Application

函数返回应用核心，应用核心继承自容器，因此应用本身就是一个容器。

#### route($prefix = null, callable $callback = null): \FastD\Routing\RouteCollection

路由函数，当 `$prefix` 和 `$callback` 都为空的时候，直接返回路由集合。

当需要设置路由组的时候，则需要 `$prefix` 和 `$callback` 配合设置, 如:

```php
route("/", function () {
    route()->get("/hello/{name}", "IndexController@sayHello");
});
```

#### config(): \FastD\Config\Config

返回配置对象，具体操作可以查看: [配置](https://github.com/JanHuang/config)

#### request(): \Psr\Http\Message\ServerRequestInterface

返回 PSR7 ServerRequestInterface 对象, 具体操作可以查看: [HTTP](https://github.com/JanHuang/http)

#### json(array $content = [], $statusCode = Response::HTTP_OK): \FastD\Http\JsonResponse

框架中返回均需要返回 `Psr\Http\Message\ResponseInterface` 接口，在函数中，[JsonResponse](https://github.com/JanHuang/http/blob/master/src/JsonResponse.php) 继承 [Response](https://github.com/JanHuang/http/blob/master/src/Response.php) 并实现相关接口，因此返回响应必须要返回实现 `Psr\Http\Message\ResponseInterface` 实现类。

#### binary(array $content = [], $statusCode = Response::HTTP_OK, array $headers = []): \FastD\Http\Response

框架中返回均需要返回 `Psr\Http\Message\ResponseInterface` 接口，在函数中，[JsonResponse](https://github.com/JanHuang/http/blob/master/src/JsonResponse.php) 继承 [Response](https://github.com/JanHuang/http/blob/master/src/Response.php) 并实现相关接口，因此返回响应必须要返回实现 `Psr\Http\Message\ResponseInterface` 实现类。

#### abort($statusCode, $message = null)

终端执行，抛出异常。异常输出控制请参考: [应用配置](3-1-configuration.md)

#### logger(): \Monolog\Logger

返回 monolog 对象，框架默认提供两种日志，一个是信息日志，一个是错误日志，分别在 [配置]() 中设置，如果需要操作日志并且创建更多日志，请使用该函数获取 Logger 对象，然后进行操作。

更多 monolog 操作请看: [Monolog](https://github.com/Seldaek/monolog)

#### cache(): \Symfony\Component\Cache\Adapter\AbstractAdapter

缓存提供器依赖于 [Symfony/cache](https://symfony.com/doc/current/components/cache.html) ，缓存函数返回一个 AbstractAdapter 对象，具体操作可以查看文档。如果无法满足业务需求，可以自定义服务提供器，对其进行代替.

#### database(): \Medoo

框架默认的 `DatabaseServiceProvider` 是提供 medoo 操作，不提供具体的 ORM 等关系操作，如果需要自定义数据库操作，可以通过实现自己的 ServiceProvider 进行扩展。

具体的操作和使用方式请查看: [服务提供器](3-6-service-provider.md)

#### server(): \swoole_server

server 函数返回 swoole 对象，在 swoole 启动的时候会自动赋值，可在控制器中使用 server 对象进行操作。 

下一节: [服务提供器](3-8-service-provider.md)
