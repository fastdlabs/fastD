# 异常与日志处理

框架中提供基础错误处理，可以集中式处理各种异常。日志默认存储路径在 `runtime/logs` 中，可以通过业务需求，将日志集中发送到一个日志服务器中，FastD 正在研发解决方案，敬请期待: [LogViewer](4-5-fastd-log-viewer.md)

### 异常

根据配置, 框架中的异常会通过 `json` 的形式返回到客户端, 并记录到日志.

应用基础配置中异常处理配置示例:
```php
'exception' => [
    'response' => function (Exception $e) {
        return [
            'msg' => $e->getMessage(),
            'code' => $e->getCode(),
        ];
    },
    'log' => function (Exception $e) {
        return [
            'msg' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => explode("\n", $e->getTraceAsString()),
        ];
    }
],
```

在此配置下的响应示例:
```
HTTP 404 Not Found
```

```json
{
    "msg": "Route \"/d\" is not found.",
    "code": 404
}
```

实现原理非常简单: 

```php
<?php

// ...
$handle = config()->get('exception.response');
$response = json($handle($e), $statusCode);
// ...
```

### 日志处理

日志服务依赖于 [monolog](https://github.com/Seldaek/monolog)，当应用程序发生异常时，程序会将异常信息记录在日志中。

> 3.1 开始日志配置增强至数组处理，支持自定义日志格式和传输

配置: 

```php
<?php
return [
    'log' => [
        [\Monolog\Handler\StreamHandler::class, 'error.log', \Monolog\Logger::ERROR]
    ],
];
```

数组接受 4 个参数，分别解析成: handler, log file, level, formatter

实现原理: 

```php
<?php
$logger = new \Monolog\Logger(app()->getName());
list($handle, $name, $level, $format) = array_pad([\Monolog\Handler\StreamHandler::class, 'error.log', \Monolog\Logger::ERROR], 4, null);

if (is_string($handle)) {
    $handle = new $handle($path.'/'.$name, $level);
}
null !== $format && $handle->setFormatter(is_string($format) ? new \Monolog\Formatter\LineFormatter($format) : $format);
$logger->pushHandler($handle);
```

### 集中式日志处理

当我们应用分布过多的时候，就需要考虑如何将日志集中管理起来，方便排查，分析。如果利用 Swoole 即将是一件容易实现的事情。启动我们的日志服务器，然后将每个应用日志都投递到日志服务器中。

应用中仅需要提交数据，数据处理就可以在日志服务器中完成。monolog 中可以使用 [monolog-reader](https://github.com/RunnerLee/monolog-reader) 进行解析。

```php
<?php
return [
    'log' => [
        [new \Monolog\Handler\SocketHandler('schema://host:port'), 'error.log', \Monolog\Logger::ERROR]
    ],
];
```

服务器接收提交信息后，对日志进行解析，批量入库，然后对应做响应的处理即可。

### 访问日志
访问日志已经移除, 但可通过后置中间件来轻松实现相同功能.

首先创建一个后置中间件, 在中间件中调用 `Logger` 写入日志.
```php
<?php

namespace Middleware;

use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AccessLoggerMiddleware extends Middleware
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {

        $response = $next($request);
        app()->get('access_logger')->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath() . ' ' . $response->getStatusCode(),
            [
                'get' => $request->getQueryParams(),
                'body' => $request->getParsedBody(),
                'header' => $request->getHeaders(),
                'server' => $request->getServerParams(),
            ]
        );

        return $response;
    }
}

```

然后通过服务提供者, 向容器中注册访问日志的 `Logger`, 并将访问日志中间件注册到全局
```php
<?php
namespace Provider;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Middleware\AccessLoggerMiddleware;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AppServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
    
        /**
         * 注册中间件
         */
        $container->get('dispatcher')->withAddMiddleware(new AccessLoggerMiddleware());
        
        /**
         * 注册 Logger
         */
        $container->add('access_logger', function () {
            $logger = new Logger(app()->getName());
            $logger->pushHandler(
                (new StreamHandler(
                    app()->getPath() . '/runtime/logs/access.log',
                    Logger::INFO
                ))
                ->setFormatter(new LineFormatter())
            );
        });
    }
}
```

下一节: [应用配置](3-1-configuration.md)
