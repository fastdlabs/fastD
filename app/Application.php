<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/7
 * Time: 上午1:59
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

use FastD\Framework\Kernel\AppKernel;
use \FastD\Container\Container;
use \FastD\Config\Config;

/**
 * Class Application
 *
 */
class Application extends AppKernel
{
    /**
     * Register project bundles into the kernel.
     *
     * @return array
     */
    public function registerBundles()
    {
        $bundles = [];

        if ($this->isDebug()) {
            $bundles[] = new \Welcome\WelcomeBundle();
        }

        return $bundles;
    }

    /**
     * @param Container $container
     */
    public function registerService(Container $container)
    {
        //$container->set('name', 'class');
        // static
        // $container->set('name', 'class::method');
    }


    /**
     * @param Config $config
     */
    public function registerConfigurationVariable(Config $config)
    {
//        $config->setVariable('%name%', 'value');
    }

    /**
     * Register application configuration
     *
     * @param \FastD\Config\Config
     * @return void
     */
    public function registerConfiguration(Config $config)
    {
        $config->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.php');
    }
}