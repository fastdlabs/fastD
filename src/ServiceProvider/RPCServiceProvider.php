<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\ServiceProvider;


use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;

/**
 * Class RPCServiceProvider
 * @package FastD\ServiceProvider
 */
class RPCServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $config = app()->getPath().'/config/rpc.php';
        if (file_exists($config)) {
            config()->merge([
                'rpc' => load($config)
            ]);
        }
    }
}