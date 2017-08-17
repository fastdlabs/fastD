# 目录结构 

```
config
    app.php             应用默认配置
    config.php          用户自定义配置
    database.php        数据库配置
    routes.php          路由配置
    server.php          swoole 服务器配置
    cache.php           缓存配置
database                
    schema              数据库迁移文件
    dataset             dbunit 数据集
src
    Console             控制台命令
    Controller          控制器
    Middleware          中间件
    ServiceProvider     自定义服务提供者
    Model               数据模型
    Testing             单元测试
bin
    console             命令行管理
    server              swoole 服务
    client              客户端
web
    index.php           应用入口文件
runtime                 程序运行数据目录
    pid                 服务器 pid 文件目录
    logs                日志目录
    cache               文件缓存目录
    process             进程 pid 文件目录
```

源代码均放置在 src 目录，如果目录并不能满足业务需求，可以通过调整 `composer.json` 文件进行适配。

下一节: [框架执行流程图](1-4-flow.md)

