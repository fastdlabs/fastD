# 队列

3.2 版本开始，加入 Swoole Server 连接池，在 Swoole Server中，回调 `workerStart` 自动启动连接池。

#### 连接池实现

在 Server `onWokerStart` 回调中，程序会调用 `app()` 函数递归所有服务，若服务实现自 `FastD\Pool\PoolInterface` 接口，那么在 Server 启动的时候，就会自动调用 `initPool` 方法，在该方法下执行连接，连接后除非 Worker 中断，否则会一直连接。

```php
<?php

namespace FastD\Pool;

use Medoo\Medoo;
use FastD\Model\Database;

class DatabasePool implements PoolInterface
{
    /**
     * @var Medoo[]
     */
    protected $connections = [];

    /**
     * @var array
     */
    protected $config;

    /**
     * Database constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $key
     *
     * @return Database
     */
    public function getConnection($key)
    {
        if (!isset($this->connections[$key])) {
            $config = $this->config[$key];
            $this->connections[$key] = new Database([
                'database_type' => isset($config['adapter']) ? $config['adapter'] : 'mysql',
                'database_name' => $config['name'],
                'server' => $config['host'],
                'username' => $config['user'],
                'password' => $config['pass'],
                'charset' => isset($config['charset']) ? $config['charset'] : 'utf8',
                'port' => isset($config['port']) ? $config['port'] : 3306,
                'prefix' => isset($config['prefix']) ? $config['prefix'] : '',
            ]);
        }

        return $this->connections[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function initPool()
    {
        foreach ($this->config as $name => $config) {
            $this->getConnection($name);
        }
    }
}
```

然后通过 ServiceProvider 注入到容器中, 可参考[ServiceProvider](3-8-service-provider.md)

启动服务器，就会连接到每个 worker 当中，要注意的是，所有每个 worker 都是独立的，也就是如果开 20 个 worker，就会产生 20 个连接。

```

Server: fast-d
App version 2.0.0 (dev)
Swoole version 1.9.5
PID file: /Users/janhuang/Documents/htdocs/me/fastd/fastd/tests/config/../runtime/pid/fast-d.pid, PID: 23683
Server udp://0.0.0.0:9527
Server Master[23683] is started
Server Worker[23695] is started [15]
Server Worker[23690] is started [10]
Server Worker[23691] is started [11]
Server Worker[23692] is started [12]
Server Worker[23693] is started [13]
Server Worker[23694] is started [14]
Server Worker[23696] is started [16]
Server Worker[23697] is started [17]
Server Worker[23698] is started [18]
Server Worker[23699] is started [19]
Server Worker[23700] is started [20]
Server Worker[23701] is started [21]
Server Worker[23702] is started [22]
Server Worker[23703] is started [23]
Server Worker[23704] is started [24]
Server Worker[23705] is started [25]
Server Worker[23706] is started [26]
Server Worker[23707] is started [27]
Server Worker[23708] is started [28]
Server Worker[23709] is started [29]
Server Worker[23710] is started [0]
Server Worker[23711] is started [1]
Server Worker[23712] is started [2]
Server Worker[23713] is started [3]
Server Worker[23714] is started [4]
Server Worker[23715] is started [5]
Server Worker[23716] is started [6]
Server Worker[23717] is started [7]
Server Worker[23718] is started [8]
Server Manager[23689] is started
Server Worker[23719] is started [9]
```

下一节: [扩展](3-12-extend.md)
