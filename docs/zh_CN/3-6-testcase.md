# 单元测试

单元测试依赖于 [testing](https://github.com/JanHuang/testing) 底层封装 Http Server Request 对象，并且增加 `Faker` 功能。

> 3.1 开始，FastD\Test\TestCase 改为 FastD\TestCase

```php
use FastD\TestCase;


class IndexControllerTest extends TestCase
{
    public function testSayHello()
    {
        $request = $this->request('GET', '/');

        $response = $this->handleRequest($request);

        $this->response($response, ['foo' => 'bar']);
    }
}
```

单元测试尽量模拟 Http 请求，对响应结果进行匹配校对，来达到预期的方式进行测试。

### 数据库测试

数据库 Testing 需要依赖 `phpunit/dbunit` 进行扩展，引用自 dbunit 扩展 trait，实现 dataset connection 方法接口

具体可参考: [数据库测试](https://phpunit.de/manual/current/zh_cn/database.html)

数据库测试支持无数据库测试，当开发者不配置数据库连接的时候，框架选择普通默认测试，不创建数据库连接，和单元测试保持一致。

数据集数据存放在: `database/dataset`，命名规则为: `{table}.yml`，内部定义为每一个数组为一行记录。

```yml

    id: 1
    content: "Hello buddy!"
    user: "joe"
    created: 2010-04-24 17:15:23
```

此处要注意数据库的测试流程，每个测试进程完成后会重置数据库。

下一节: [辅助函数](3-7-helpers.md)
