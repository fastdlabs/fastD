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
class AppKernel extends \Dobee\Kernel\Kernel
{
    /**
     * Register project bundles into the kernel.
     *
     * @return array
     */
    public function registerBundles()
    {
        return array(
            new \DemoBundle\DemoBundle(),
            new \MysqlDemoBundle\MysqlDemoBundle(),
        );
    }

    /**
     * You can register global configuration variables. But you must return array()
     * examples:
     *      return array( 'demo' => 'name' );
     *
     * @return array
     */
    public function registerConfigurationVariable()
    {
        return array(
            'date' => date('Y-m-d'),
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
        // TODO: Implement registerPlugins() method.
    }

    /**
     * Register app kernel configuration.
     *
     * @param $configuration
     * @return void
     */
    public function registerContainerConfiguration(\Dobee\Kernel\Configuration\Config\LoaderInterface $configuration)
    {
        $configuration->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}