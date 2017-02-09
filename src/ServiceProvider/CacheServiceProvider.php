<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\ServiceProvider;


use FastD\Config\ConfigLoader;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class CacheServiceProvider implements ServiceProviderInterface
{
    protected $cache;

    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $config = config()->get('cache');

        $container->add('cache', function () use ($config) {
            if (null === $this->cache) {
                switch ($config['adapter']) {
                    case RedisAdapter::class:
                        $this->cache = new RedisAdapter(
                            RedisAdapter::createConnection($config['params']['dsn']),
                            isset($config['params']['namespace']) ? $config['params']['namespace'] : '',
                            isset($config['params']['lifetime']) ? $config['params']['lifetime'] : ''
                        );
                        break;
                    default:
                        $this->cache = new $config['adapter'](
                            isset($config['params']['namespace']) ? $config['params']['namespace'] : '',
                            isset($config['params']['lifetime']) ? $config['params']['lifetime'] : '',
                            isset($config['params']['directory']) ? $config['params']['directory'] : ''
                        );
                }
            }
            return $this->cache;
        });

        config()->merge($config);

        unset($config);
    }
}