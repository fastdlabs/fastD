# 关于 FastD 

:rocket:FastD 是一个为构建高性能 API 而生的框架，去除前版本多余的组件，针对API进行特殊的优化处理，让开发者更加专注应用。

FastD 提供灵活配置的核心，由于为了性能，提供的组件当不能满足当下业务时，可以通过自定义对核心，和服务进行扩展。

由于 FastD 是支持 Swoole 扩展，因此可以通过 Swoole 来开发更多的应用，打破传统web模式，还有很多想象空间。

#### 适用场景

* 适用于专注 API 端的开发
* Swoole 服务端开发

#### 环境要求

- [x] PHP >= 5.6
- [x] ext-curl
- [x] ext-pdo
- [x] ext-swoole

#### 相关扩展

- [x] [FastD ServiceProvider](https://github.com/linghit/service-provider)
- [x] [FastD Viewer](https://github.com/JanHuang/viewer)
- [x] [FastD ORM](https://github.com/zqhong/fastd-eloquent)
- [x] [FastD QConf](https://github.com/JanHuang/QConfServiceProvider)

下一节: [安装](1-2-installing.md)