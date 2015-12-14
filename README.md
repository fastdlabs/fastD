#FastD PHP Simple Framework

##Requirement

* PHP 5.5+

##Optional

* [php-ext-swoole 1.7.20+](https://github.com/swoole/swoole-src)

##Install

`composer -vvv create-project path fastd/fastd`

#Tutorial

##Documentation

[Documentation](http://www.fast-d.cn/docs/index.html)

##Rewrite rules

###Swoole Http Server

configuration: `app/config/server.php`

```
php app/console http:server start
```

`host:port/pathinfo`

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


