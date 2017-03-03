# 修改日志

##### 3.1.0

* 修改 Server 配置
* 删除过多无用配置
* 修改 services 配置项为 providers
* 新增路由命令
* 支持自定义 Server, 内置 TCPServer、HTTPServer
* 集成 testing 测试
* 标准命名
* 添加 Servitization 服务化模块
* 新增路由组中间件
* 去除 Http 目录，改为 Controller，支持HTTP，TCP调用
* 增加 Swoole 客户端命令行， `php bin/console client {host} {port} [--method|-m GET|POST]`
* 调整 config/database.php 配置项

##### 3.0.0

* 新增 Swoole 监控端
* 新增 Swoole 发现端
* 更新文档
* 新增文档管理
* 新增 config 独立配置

