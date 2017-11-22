<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Servitization\ServiceRegister;

use Symfony\Component\Cache\Adapter\RedisAdapter;

/**
 * Class RedisRegisterCenter.
 */
class RedisRegisterCenter implements RegisterCenterInterface
{
    /**
     * @var \Predis\Client|\Redis
     */
    protected $redis;

    /**
     * RedisRegisterCenter constructor.
     */
    public function __construct()
    {
        $host = config()->get('rpc.register.params.dsn', 'redis://127.0.0.1:6379/1');

        $this->redis = RedisAdapter::createConnection($host);
    }

    /**
     * @param $key
     * @param $hash
     * @param $value
     *
     * @return mixed
     */
    public function set($key, $hash, $value)
    {
        return $this->redis->hSet($key, $hash, $value);
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->redis->hGetAll($key);
    }
}
