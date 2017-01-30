# Fast-D PHP High Performance API Framework

[![Latest Stable Version](https://poser.pugx.org/fastd/fastd/v/stable)](https://packagist.org/packages/fastd/fastd) [![Latest Unstable Version](https://poser.pugx.org/fastd/fastd/v/unstable)](https://packagist.org/packages/fastd/fastd) [![License](https://poser.pugx.org/fastd/fastd/license)](https://packagist.org/packages/fastd/fastd)

### Requirements

* PHP >= 5.6
* ext-curl
* ext-pdo
* [ext-swoole]

### Installation

```
composer create-project "fastd/api" fastd -vvv
```

### Documentation

[中文文档](docs/zh_CN/readme.md)

### Nginx Server

```
server 
{
    location @rewriteapp {
        rewrite ^(.*)$ /index.php$1 last;
    }
    location ~ \.php {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_split_path_info ^(.+.php/)(/.*)$;
        include       fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### Swoole Http Server

```
server 
{
    location / {
        try_files $uri /$uri @rewriteapp;
    }
    location @rewriteapp {
        proxy_pass http://127.0.0.1:9527; # Swoole Server Listen
    }
}
```

### Support

如果你在使用中遇到问题，请联系: [bboyjanhuang@gmail.com](mailto:bboyjanhuang@gmail.com). 微博: [编码侠](http://weibo.com/ecbboyjan)

## License MIT
