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
            new \TestBundle\TestBundle(),
        );
    }

    /**
     * Register project global configuration.
     *
     * @param \Dobee\Kernel\Configuration\Configuration\LoaderInterface $loaderInterface
     * @return void
     */
    public function registerContainerConfiguration(\Dobee\Kernel\Configuration\Configuration\LoaderInterface $loaderInterface)
    {
        $loaderInterface->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}