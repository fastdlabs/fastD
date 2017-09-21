<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Pool;

use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

/**
 * Class CachePool.
 */
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
     * @param $key
     *
     * @return AbstractAdapter
     */
    public function getCache($key)
    {
        if (!isset($this->caches[$key])) {
            if (!isset($this->config[$key])) {
                throw new \LogicException(sprintf('No set %s cache', $key));
            }
            $config = $this->config[$key];
            switch ($config['adapter']) {
                case RedisAdapter::class:
                    $this->caches[$key] = new RedisAdapter(
                        RedisAdapter::createConnection($config['params']['dsn']),
                        isset($config['params']['namespace']) ? $config['params']['namespace'] : '',
                        isset($config['params']['lifetime']) ? $config['params']['lifetime'] : ''
                    );

                    break;
                default:
                    $this->caches[$key] = new $config['adapter'](
                        isset($config['params']['namespace']) ? $config['params']['namespace'] : '',
                        isset($config['params']['lifetime']) ? $config['params']['lifetime'] : '',
                        isset($config['params']['directory']) ? $config['params']['directory'] : app()->getPath().'/runtime/cache'
                    );
            }
        }

        return $this->caches[$key];
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
