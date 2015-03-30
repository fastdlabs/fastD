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
class Application extends \Dobee\Framework\AppKernel
{
    /**
     * Register project bundles into the kernel.
     *
     * @return array
     */
    public function registerBundles()
    {
        return array(
            new \Welcome\Welcome(),
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
    public function registerPlugins()
    {
        return array(
        );
    }

    /**
     * @return array
     */
    public function registerConfigVariable()
    {
        return array(
            'root_path' => $this->getRootPath(),
            'env'       => $this->getEnvironment(),
            'Ymd'       => date('Ymd'),
        );
    }

    /**
     * Register application configuration
     *
     * @param \Dobee\Configuration\Config $config
     * @return void
     */
    public function registerConfiguration(\Dobee\Configuration\Config $config)
    {
        $config->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}