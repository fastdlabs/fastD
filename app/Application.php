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
class Application extends \FastD\Framework\Kernel\AppKernel
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
     * Register custom kernel plugins.
     * Must return array.
     * examples:
     *  return array(
     *      "Monolog\\Logger"
     *  )
     *
     * @return array
     */
    public function registerService(\FastD\Container\Container $container)
    {
        //$container->set('name', 'class');
        // static
        // $container->set('name', 'class::method');
    }

    /**
     * @return array
     */
    public function registerConfigurationVariable(\FastD\Config\Config $config)
    {
//        $config->setVariable('%name%', 'value');
    }

    /**
     * Register application configuration
     *
     * @param \FastD\Config\Config
     * @return void
     */
    public function registerConfiguration(\FastD\Config\Config $config)
    {
        $config->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.php');
    }
}