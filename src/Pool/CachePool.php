<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Pool;

use ReflectionClass;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
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
     * @var array
     */
    protected $redises = [];

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
     * @return AbstractAdapter|FilesystemAdapter|RedisAdapter
     * @throws \ReflectionException
     */
    protected function connect($key)
    {
        if (!isset($this->config[$key])) {
            throw new \LogicException(sprintf('No set %s cache', $key));
        }
        $config = $this->config[$key];

        // 解决使用了自定义的 RedisAdapter 时无法正常创建的问题
        if (
            $config['adapter'] === RedisAdapter::class
            || (new ReflectionClass($config['adapter']))->isSubclassOf(RedisAdapter::class)) {
            return $this->getRedisAdapter($config, $key);
        }
        return $this->getAdapter($config);
    }

    /**
     * @param $key
     * @return AbstractAdapter
     * @throws \ReflectionException
     */
    public function getCache($key)
    {
        if (!isset($this->caches[$key])) {
            $this->caches[$key] = $this->connect($key);
        }

        if (isset($this->redises[$key])) {
            if (
                null === $this->redises[$key]['connect']
                || false === $this->redises[$key]['connect']->ping()
            ) {
                $this->caches[$key] = $this->connect($key);
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

    /**
     * @param array $config
     * @return FilesystemAdapter|RedisAdapter
     */
    protected function getRedisAdapter(array $config, $key)
    {
        $connect = null;
        try {
            $connect = RedisAdapter::createConnection($config['params']['dsn']);
            $cache = new $config['adapter'](
                $connect,
                isset($config['params']['namespace']) ? $config['params']['namespace'] : '',
                isset($config['params']['lifetime']) ? $config['params']['lifetime'] : ''
            );
        } catch (\Exception $e) {
            $cache = new FilesystemAdapter('', 0, '/tmp/cache');
        }

        $this->redises[$key] = [
            'connect' => $connect,
            'driver' => RedisAdapter::class,
        ];

        return $cache;
    }

    /**
     * @param array $config
     * @return AbstractAdapter
     */
    protected function getAdapter(array $config)
    {
        return new $config['adapter'](
            isset($config['params']['namespace']) ? $config['params']['namespace'] : '',
            isset($config['params']['lifetime']) ? $config['params']['lifetime'] : '',
            isset($config['params']['directory']) ? $config['params']['directory'] : app()->getPath().'/runtime/cache'
        );
    }
}
