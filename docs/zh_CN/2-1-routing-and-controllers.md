# 路由与控制器

==========

路由的提供来源于 [routing](https://github.com/JanHuang/routing) 组件，提供高性能的路由配置和解析处理，良好地支持 RESTful。

路由配置文件存放于 `config/routes.php` 文件中。

### 使用

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


下一节: [请求](2-2-request-handling.md)
