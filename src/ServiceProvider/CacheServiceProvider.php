<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\ServiceProvider;


use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Servitization\PoolInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

/**
 * Class Cache
 * @package FastD\ServiceProvider
 */
class Cache implements PoolInterface
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
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $name
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
                        isset($config['params']['directory']) ? $config['params']['directory'] : ''
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

/**
 * Class CacheServiceProvider
 * @package FastD\ServiceProvider
 */
class CacheServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $config = $container->get('config')->get('cache');

        $container->add('cache', new Cache($config));

        unset($config);
    }
}