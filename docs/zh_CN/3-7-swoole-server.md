# Swoole 服务器

Swoole 服务器依赖于 [swoole](https://github.com/JanHuang/swoole) 并且提供灵活优雅的实现方式。

> swoole 必须依赖于 ext-swoole 扩展，并且版本 >= 1.8.5

swoole 服务器的配置文件存放在 `config/server.php` 中，其中 `listen` 选项是必填，`options` 配置项请看 [server 配置选项](http://wiki.swoole.com/wiki/page/274.html)

```php
$ php bin/server {start|status|stop|reload}
```

默认操作方式是 `status`，可用于简单查看进程状态。

> 访问的方式与 FPM 下访问保持一致。开发环境下推荐使用 FPM，生产环境可以尝试用 Swoole 代替 FPM，如果有必要的话

### 守护进程

以守护进程的方式启动，只需要加一个 `--daemon|-d` 选项即可。

```php
$ php bin/server {start|status|stop|reload} -d > /dev/null
```

启动守护进程需要屏蔽输出，屏蔽输出仅仅是屏蔽输出而已，并不会对程序和性能造成影响。

以上即可通过不修改代码，支持 Swoole 操作。

下一节: [扩展](3-8-extend.md)
