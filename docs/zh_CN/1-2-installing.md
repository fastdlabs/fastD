# 安装 FastD 

#### Composer 安装

##### 1.0 如果没有安装 Composer 

```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

##### 1.1 安装 FastD

```
composer create-project "fastd/fastd" fastd -vvv 
```

#### 2.0 确认安装完成

通过终端或者浏览器访问当前web目录

#### 3.0 安装 Swoole 扩展

```
$ pecl install swoole
```

#### 4.0 确认 Swoole 扩展成功安装

```
$ php -r 'echo SWOOLE_VERSION;'
```

#### 5.0 启动 Swoole 服务器

```php
php bin/server 
```

下一节: [目录结构](1-3-directory-structure.md)
