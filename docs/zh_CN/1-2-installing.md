# 安装 FastD 

> 如果使用浏览器访问入口，需要给项目配置虚拟域名，将路径指向项目的 web 目录

##### 1 如果没有安装 Composer 

```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

#### 2 安装 Swoole 扩展

```
$ pecl install swoole
```

##### 3 安装 fastd/dobee

```
$ composer create-project "fastd/dobee" dobee -vvv 
```

#### 4 启动服务器

通过浏览器或者PHP 内置 WEB 服务器访问当前web目录

**启动内置 Web 服务器**

```shell
$ cd dobee
$ php -S 127.0.0.1:9527 -t ./web 
```

**启动 Swoole**

```php
$ php bin/server 
```

浏览器访问 `127.0.0.1:9527` 即可得到结果


下一节: [目录结构](1-3-directory-structure.md)
