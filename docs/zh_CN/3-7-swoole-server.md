# Swoole 服务器

Swoole 服务器依赖于 [swoole](https://github.com/JanHuang/swoole) 提供灵活优雅的实现方式。

> swoole 必须依赖于 ext-swoole 扩展，并且版本 >= 1.8.5

swoole 服务器的配置文件存放在 `config/server.php` 中，其中 `listen` 选项是必填，`options` 配置项请看 [server 配置选项](http://wiki.swoole.com/wiki/page/274.html)

服务器通过: `php bin/server` 进行启动. 通过 `listen` 配置项进行访问

> 访问的方式与 FPM 下访问保持一致。

以上即可通过不修改代码，支持 Swoole 操作。

下一节: [扩展](3-8-extend.md)
