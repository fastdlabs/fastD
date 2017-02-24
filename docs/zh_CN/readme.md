# FastD 中文文档

框架中集成了部分使用率较高的工具，提高开发效率，不必担心的是，框架并不会因为依赖的东西多了而造成速度的变慢，程序中，仅会对使用到的文件代码进行操作。

框架执行模式: 

1. 普通模式
2. 命令行模式
3. Swoole 模式

针对不同模式，加载的对象不大相同，所以针对不同的执行环境，会有不同的操作流程来加速框架的调度效率。

修改日志
--------

* [修改日志](change-log.md)

安装与配置
--------

* [关于 FastD](1-1-about-fastd.md)
* [安装 FastD](1-2-installing.md)
* [目录结构](1-3-directory-structure.md)


基础入门
-------

* [路由与控制器](2-1-routing-and-controllers.md)
* [请求处理](2-2-request-handling.md)
* [响应处理](2-3-response-handling.md)
* [认证授权](2-4-authorization.md)
* [异常与日志处理](2-5-exception-logger-handling.md)
* [API文档](2-6-docuemnt.md)

高级
-------

* [配置](3-1-configuration.md)
* [中间件](3-2-middleware.md)
* [数据库medoo](3-3-database.md)
* [缓存](3-4-cache.md)
* [命令行](3-5-console.md)
* [单元测试](3-6-testcase.md)
* [辅助函数](3-7-helpers.md)
* [服务提供器](3-8-service-provider.md)
* [Swoole服务器](3-9-swoole-server.md)
* [扩展](3-10-extend.md)


架构
---------

* [生命周期](4-1-lifecycle.md)
* [RPC服务](4-2-microservice.md)

### Support

如果你在使用中遇到问题，请联系: [bboyjanhuang@gmail.com](mailto:bboyjanhuang@gmail.com). 微博: [编码侠](http://weibo.com/ecbboyjan)

## License MIT