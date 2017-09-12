# 扩展

框架以灵巧的方式进行服务提供，大部分的服务均通过 composer.json 与 [服务提供器进](3-6-service-provider.md) 进行依赖。

如数据库服务通过依赖 `catfan/Medoo`, 通过 [DatabaseServiceProvider.php](../../src/ServiceProvider/DatabaseServiceProvider.php) 进行注册到全局核心当中。

如果框架无法满足该业务需求，可以通过调配 composer 依赖，添加 ServiceProvider 到应用当中，即可完成扩展

如果还有疑问，可以参考 [ServiceProvider](../../src/ServiceProvider)

* [Eloquent ORM](https://github.com/zqhong/fastd-eloquent)

## 开发 FastD 扩展包

相信使用过 Symfony，Laravel 的朋友都知道，如果框架支持扩展包开发，或者有良好的扩展包生态支持，那么在开发上，可以说是如鱼得水，在 2.0 版本中，也是支持扩展包开发的。
 
* [FastD ServiceProvider](https://github.com/linghit/service-provider)
* [FastD Viewer](https://github.com/JanHuang/viewer)
* [FastD ORM](https://github.com/zqhong/fastd-eloquent)
* [FastD QConf](https://github.com/JanHuang/QConfServiceProvider)

下一节: [监控](3-13-monitor.md)
