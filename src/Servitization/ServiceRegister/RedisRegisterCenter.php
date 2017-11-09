<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\ServiceRegister;


use FastD\Http\Response;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;

/**
 * Class RedisRegisterCenter
 * @package FastD\Servitization\ServiceRegister
 */
class RedisRegisterCenter implements RegisterCenterInterface
{
    protected $redis;

    public function __construct()
    {
        $host = config()->get('rpc.register.params.dsn', 'redis://127.0.0.1:6379/1');

        $this->redis = RedisAdapter::createConnection($host);
    }

    /**
     * @param $key
     * @param $hash
     * @param $value
     * @return mixed
     */
    public function set($key, $hash, $value)
    {
        return $this->redis->hSet($key, $hash, $value);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->redis->hGetAll($key);
    }
}