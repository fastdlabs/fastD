# 异常处理

框架中的异常会通过 `json` 的形式返回到客户端，输出具体的错误信息

```json
{
    "msg": "Route \"/d\" is not found.",
    "code": 404,
    "file": "../RouteCollection.php",
    "line": 273,
    "trace": [
    ]
}
```

下一节: [应用配置](3-1-configuration.md)
