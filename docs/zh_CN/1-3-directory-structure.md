# 目录结构 

========

```
config
    app.php
    database.php
    routes.php
    server.php
src
    Console             控制台命令
    Http                
        Controller      Http 控制器
    Middleware          Http 中间件
    ServiceProvider     自定义服务提供者
    Testing             单元测试
bin
    console
    server
web
    index.php
```

源代码均放置在 src 目录，如果目录并不能满足业务需求，可以通过手动修改方式进行调整。

下一节: [路由与控制器](2-1-routing-and-controllers.md)

