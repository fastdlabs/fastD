# Fast-D PHP High Performance API Framework

[![Latest Stable Version](https://poser.pugx.org/fastd/fastd/v/stable)](https://packagist.org/packages/fastd/fastd) [![Latest Unstable Version](https://poser.pugx.org/fastd/fastd/v/unstable)](https://packagist.org/packages/fastd/fastd) [![License](https://poser.pugx.org/fastd/fastd/license)](https://packagist.org/packages/fastd/fastd)

#### Requirements

* PHP >= 5.6
* ext-curl
* ext-pdo
* [ext-swoole]

#### Installation

```
composer create-require "fastd/fastd:3.0.x-dev"
```

#### Documentation

[中文文档](docs/zh_CN)

#### Swoole Http Server

```
server {
    listen     80;
    server_name server;
    index index.php;
    root /home/runner/Code/fastd/fastd.standard/web;
    location / {
        try_files $uri /$uri @rewriteapp;
    }
    location @rewriteapp {
        proxy_pass http://127.0.0.1:9527; # Swoole Server Listen
    }
}
```

#### Nginx Server

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
  }
}
```

## License MIT
