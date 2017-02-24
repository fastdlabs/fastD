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
use FastD\Swoole\Server;

/**
 * Class SwooleServiceProvider
 * @package FastD\ServiceProvider
 */
class SwooleServiceProvider implements ServiceProviderInterface
{
    public $server;

    /**
     * SwooleServiceProvider constructor.
     * @param Server $server
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $container->get('config')->load(app()->getPath() . '/config/server.php');
        $container->add('server', $this->server);
        $this->server = null;
    }
}