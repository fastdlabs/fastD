# 修改日志

#### 3.2

* 新增进程管理命令，新增 process.php 配置文件
* 调整注册命令行
* 新增 `binary`, `version` 等辅助函数
* 优化 application 日志处理
* 新增 `FastD\Logger\Formatter\StashForamtter` 日志格式，支持 `logstash`。点击前往: [教程](http://fastdlabs.com/blog/13)
* 新增 `FastD\ServiceProvider\MoltenServiceProvider`，支持 `zipkin` 调用链。点击前往: [教程](http://fastdlabs.com/blog/14)
* 替换 migration 为 [fastd/migration](https://github.com/JanHuang/migration)


#### 3.1.0 released candidate 4

* 新增通用缓存中间件
* bug fixed
* 内置migration `is_available`, `created_at`, `updated_at` 三个数据库标准字段,
* 新增 abort 中断函数

#### 3.1.0 release candidate 3

* 修复单元测试数据重置 bug

##### 3.1.0 release candidate 2

* 增强日志配置，添加增加第4个参数，意为自定义日志格式，默认为空，实现原理: [点击](2-5-exception-logger-handling.md)
* 增加错误输出配置，可以在生产环境隐藏 trace 输出
* 修改单元测试命名空间，由 `FastD\Test\TestCase` 改为 `FastD\TestCase`
* 新增增加应用 `shutdown` 方法，主要为了后期日志处理，统计埋点。
* 更新文档

##### 3.1.0 release candidate 1

* 增强日志配置选项,改为数组配置，支持自定义日志位置，等级，默认 `Monolog\Logger::NOTICE`
* 增加缓存默认路径
* 新增 `FastD\Model\Database` 对象，新增连接存活检测，防止连接池连接挂掉导致进程异常
* 新增 TestCase 测试方法，使测试用例更加人性化

##### 3.1.0-beta2

* 统一文件命名，不影响操作
* 新增 model:create 命令
* 新增 controller:create 命令
* 升级 testing，增加 dbunit。生产环境下增加 composer 选项 `--no-dev`
* 调整 seed:create 结构，由 up 方法改为 setUp，setUp 方法返回表对象，并新增 dataSet 方法
* 调整 seed:create 命令生成位置
* 新增 client 客户端命令
* 更新文档
* 去除配置文件 app.php log 配置项日志键名，改为一维数组，可配置多个日志对象
* 实现 dbunit 测试套件

##### 3.1.0-beta1

* 修改 Server 配置
* 删除过多无用配置
* 修改 services 配置项为 providers
* 新增路由命令
* 内置 TCPServer、HTTPServer
* 集成 testing, dbunit 测试
* 添加 Servitization 服务化模块
* 新增路由组中间件
* 去除 Http 目录，改为 Controller，支持HTTP，TCP调用
* 增加 Swoole 客户端命令行， `php bin/console client {host} {port} {service} {arguments}
* 调整 config/database.php 配置项
* 修改进程pid文件，应用缓存文件目录 runtime
* 新增数据库结构处理命令 `seed:create`, `seed:run` 
* 新增 Seed 数据库结构
* 添加状态监控服务
* 新增连接池

##### 3.0.0

* 新增 Swoole 监控端
* 新增 Swoole 发现端
* 更新文档
* 新增文档管理
* 新增 config 独立配置

