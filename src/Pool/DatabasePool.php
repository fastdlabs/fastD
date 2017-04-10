<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

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
