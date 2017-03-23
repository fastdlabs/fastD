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
use Medoo\Medoo;
use FastD\Servitization\PoolInterface;

/**
 * Class Database
 * @package FastD\ServiceProvider
 */
class Database implements PoolInterface
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
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $key
     * @return Medoo
     */
    public function getConnection($key)
    {
        if (!isset($this->connections[$key])) {
            $config = $this->config[$key];
            $this->connections[$key] = new Medoo([
                'database_type' => isset($config['adapter']) ? $config['adapter'] : 'mysql',
                'database_name' => $config['name'],
                'server' => $config['host'],
                'username' => $config['user'],
                'password' => $config['pass'],
                'charset' => isset($config['charset']) ? $config['charset'] : 'utf8',
                'port' => isset($config['port']) ? $config['port'] : 3306,
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

/**
 * Class DatabaseServiceProvider
 * @package FastD\ServiceProvider
 */
class DatabaseServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container)
    {
        $config = config()->get('database', []);

        $container->add('database', new Database($config));

        unset($config);
    }
}