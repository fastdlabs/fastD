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

/**
 * Class DatabaseServiceProvider
 * @package FastD\ServiceProvider
 */
class DatabaseServiceProvider implements ServiceProviderInterface
{
    protected $db;

    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container)
    {
        $config = config()->get('database', []);

        $container->add('database', function () use ($config) {
            if (null === $this->db) {
                $this->db = new Medoo([
                    'database_type' => isset($config['adapter']) ? $config['adapter'] : 'mysql',
                    'database_name' => $config['name'],
                    'server' => $config['host'],
                    'username' => $config['user'],
                    'password' => $config['pass'],
                    'charset' => isset($config['charset']) ? $config['charset'] : 'utf8',
                    'port' => isset($config['port']) ? $config['port'] : 3306,
                ]);
            }
            return $this->db;
        });

        unset($config);
    }
}