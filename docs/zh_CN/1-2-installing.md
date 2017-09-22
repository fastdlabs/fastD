# 安装 FastD 

> 如果使用浏览器访问入口，需要给项目配置虚拟域名，将路径指向项目的 web 目录

:bangbang:推荐配合 Vagrant 虚拟机使用，能够更快适应开发环境。

### Linux 环境

##### 1 如果没有安装 Composer 

```
$ curl -sS https://getcomposer.org/installer | php
$ mv composer.phar /usr/local/bin/composer
$ chown +x /usr/local/bin/composer
```

:soon:国内镜像，加速 composer 安装

```
composer config -g repo.packagist composer https://packagist.laravel-china.org
```

更多使用方式请前往: [Composer国内镜像](https://laravel-china.org/composer)

##### 2 安装 Swoole 扩展

:bangbang:推荐使用1.9.9以上版本

```
$ pecl install swoole
```

##### 3 安装 fastd/dobee

```
$ composer create-project "fastd/dobee" dobee -vvv 
$ cd dobee && composer install -vvv
```


##### 4 启动内置 Web 服务器

> 推荐在开发环境下使用，可脱离 Apache 和 Nginx，更易使用

```shell
$ cd dobee
$ php -S 127.0.0.1:9527 -t ./web
$ curl http://127.0.0.1:9527/
```

##### 启动 Swoole 服务器

```php
$ php bin/server start
$ curl http://127.0.0.1:9527/
```

### Windows 环境

因为 swoole 没有太多考虑 windows 环境，所以推荐使用虚拟机环境进行开发，Windows 仅支持传统 PHP 模式。

##### 1 安装 fastd/dobee
 
```
$ composer create-project "fastd/dobee" dobee -vvv 
```

##### 2 PHP 内置 Web 服务器

```shell
$ cd dobee
$ php -S 127.0.0.1:9527 -t ./web
$ curl http://127.0.0.1:9527/
```

##### 3 配置 apache 虚拟域名

修改 httpd.conf，开启 vhost.conf，添加虚拟与名到 vhost.conf 文件中，修改目录地址。

```apacheconfig
<VirtualHost *:80>
    DocumentRoot "/path/to/web"
    ServerName example.com
</VirtualHost>
```

映射本地 ip 到虚拟域名，修改 System32 下面的 hosts 文件

##### 4 配置 nginx 配置

```
server
{
    listen     {server port};
    index index.php;
    server_name {server name};
    root /path/to/dobee/web;
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }
    location ~ \.php {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include       fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### Nginx + Swoole 代理 (推荐使用 Linux 环境)

```
server 
{
    listen     {server port};
    server_name {server name};
    location / {
        proxy_pass http://127.0.0.1:9527; # Swoole Server Listen
    }
}
```

不建议完全替代 Nginx + FPM，毕竟有 Nginx 作为前端服务器，灵活和扩展性会大大提高。

下一节: [目录结构](1-3-directory-structure.md)
