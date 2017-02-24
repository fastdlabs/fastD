# 单元测试

单元测试依赖于 [testing](https://github.com/JanHuang/testing) 底层封装 Http Server Request 对象，并且增加 `Faker` 功能。

```php
use FastD\Test\TestCase;


class IndexControllerTest extends TestCase
{
    public function testSayHello()
    {
        $request = $this->request('GET', '/');

        $response = $this->app->handleRequest($request);

        $this->response($response, ['foo' => 'bar']);
    }
}
```

单元测试尽量模拟 Http 请求，对响应结果进行匹配校对，来达到预期的方式进行测试。

下一节: [辅助函数](3-7-helpers.md)
