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
     * @return FilesystemAdapter|RedisAdapter
     */
    protected function connect($key)
    {
        if (!isset($this->config[$key])) {
            throw new \LogicException(sprintf('No set %s cache', $key));
        }
        $config = $this->config[$key];
        switch ($config['adapter']) {
            case RedisAdapter::class:
                $connect = null;
                try {
                    $connect = RedisAdapter::createConnection($config['params']['dsn']);
                    $cache = new RedisAdapter(
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

                break;
            default:
                $cache = new $config['adapter'](
                    isset($config['params']['namespace']) ? $config['params']['namespace'] : '',
                    isset($config['params']['lifetime']) ? $config['params']['lifetime'] : '',
                    isset($config['params']['directory']) ? $config['params']['directory'] : app()->getPath().'/runtime/cache'
                );
        }

        return $cache;
    }

    /**
     * @param $key
     *
     * @return AbstractAdapter
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

            if (app()->has('logger') && $this->caches[$key] instanceof AbstractAdapter) {
                $this->caches[$key]->setLogger(logger());
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
