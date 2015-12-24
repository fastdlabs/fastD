#FastD 简单灵活的PHP开发框架

##环境要求

* PHP 5.5+

##可选项

* [php-ext-swoole 1.7.20+](https://github.com/swoole/swoole-src)

####安装 Swoole 扩展

```
pecl install swoole
```

##安装框架

`composer -vvv create-project path fastd/fastd`

##使用

框架访问目录: `public/(dev|test|prod).php`

根据不同环境加载不同配置.

开发环境和测试环境可以使用注释来定义路由,生产环境路由需要由命令: `php app/console route:cache` 生成,节省解析时间

##文档

[文档v1.4](http://www.fast-d.cn/docs/index.html)

文档v2.0-dev 编写中......

##重写规则

###Swoole Http Server

configuration: `app/config/server.php`

```
php app/console http:server start
```

访问: `host:port/pathinfo`

服务配置: `app/config/server.php`

###Nginx + Swoole Http Server 

```
server {
        location / {
            fastcgi_index index.php;
            proxy_pass http://127.0.0.1:9600;
        }
    }
```

###Nginx

```
server {
    listen 80;
    server_name [server_name];
    root [document_root];
    index (dev|test|prod).php;
    location ~ \.php {
            fastcgi_split_path_info ^(.+.php)(/.*)$;
            fastcgi_param   PATH_INFO $fastcgi_path_info;
            fastcgi_pass 127.0.0.1:9000;
            include fastcgi.conf;
    }
}
```

#License MIT


