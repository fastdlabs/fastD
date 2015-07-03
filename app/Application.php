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
class Application extends Kernel\AppKernel
{
    /**
     * Register project bundles into the kernel.
     *
     * @return array
     */
    public function registerBundles()
    {
        return array(
            new \Welcome\Boot(),
            new \Api\V1\Boot(),
        );
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
    public function registerHelpers()
    {
        return array(
            'demo' => 'Helpers\\Demo\\Demo'
        );
    }

    /**
     * @return array
     */
    public function registerConfigVariable()
    {
        return array(

        );
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