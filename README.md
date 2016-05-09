# FastD 简单灵活的PHP开发框架

[![Latest Stable Version](https://poser.pugx.org/fastd/fastd/v/stable)](https://packagist.org/packages/fastd/fastd) [![Total Downloads](https://poser.pugx.org/fastd/fastd/downloads)](https://packagist.org/packages/fastd/fastd) [![Latest Unstable Version](https://poser.pugx.org/fastd/fastd/v/unstable)](https://packagist.org/packages/fastd/fastd) [![License](https://poser.pugx.org/fastd/fastd/license)](https://packagist.org/packages/fastd/fastd)

## 环境要求

* PHP 7+

#### 安装 Swoole 扩展

```
pecl install swoole
```

## 安装框架

`composer -vvv create-project path "fastd/fastd:2.0.x-dev"`

## 使用

框架访问目录: `public/(dev|test|prod).php`

本地地址: `http://localhost/path/to/fastd/public/dev.php/welcomebundle`

根据不同环境加载不同配置.

开发环境和测试环境可以使用注释来定义路由,生产环境路由需要由命令: `php app/console route:cache` 生成,节省解析时间

## 文档

[文档v1.x](http://www.fast-d.cn/docs/index.html)

文档v2.0-dev 编写中......

## 服务器配置

### Nginx

```
server
{
    listen       80;
    server_name  {server_name};
    index {(dev|test|prod)}.php;
    root {document_root};
    location / {
        try_files $uri @rewriteapp;
    }
    location @rewriteapp {
        rewrite ^(.*)$ /{(dev|test|prod)}.php$1 last;
    }
    location ~ \.php {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_split_path_info ^(.+.php)(/.*)$;
        include       fastcgi_params;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS              off;
    }
}
```

# License MIT
