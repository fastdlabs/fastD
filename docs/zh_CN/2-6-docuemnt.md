# API 文档

FastD 框架中已经默认集成 [swagger](https://github.com/zircote/swagger-php) 文档，仅需要通过以下命令即可生成文档，亦可通过集成的方式将文档集中到一个中心管理。

```php
php bin/console doc
```

默认将文档内容和入口生成到 `web` 目录下，两个文件，一个是 `api.json`, 另外一个是 `api.html`。

文档的方式通过注释进行处理，定义在控制器中，更具体的操作请前往 [swagger getting started](https://github.com/zircote/swagger-php/blob/master/docs/Getting-started.md)

下一节: [应用配置](3-1-configuration.md)