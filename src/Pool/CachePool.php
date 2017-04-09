<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Pool;

use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class CachePool implements PoolInterface
{
    /**
     * @var AbstractAdapter[]
     */
    protected $caches = [];

    /**
     * @var array
     */
    protected $config;

    /**
     * Cache constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $name
     *
     * @return AbstractAdapter
     */
    public function getCache($name)
    {
        if (!isset($this->caches[$name])) {
            $config = $this->config[$name];
            switch ($config['adapter']) {
                case RedisAdapter::class:
                    $this->caches[$name] = new RedisAdapter(
                        RedisAdapter::createConnection($config['params']['dsn']),
                        isset($config['params']['namespace']) ? $config['params']['namespace'] : '',
                        isset($config['params']['lifetime']) ? $config['params']['lifetime'] : ''
                    );
                    break;
                default:
                    $this->caches[$name] = new $config['adapter'](
                        isset($config['params']['namespace']) ? $config['params']['namespace'] : '',
                        isset($config['params']['lifetime']) ? $config['params']['lifetime'] : '',
                        isset($config['params']['directory']) ? $config['params']['directory'] : app()->getPath().'/runtime/cache'
                    );
            }
        }

        return $this->caches[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function initPool()
    {
        foreach ($this->config as $name => $config) {
            $this->getCache($name);
        }
    }
}
