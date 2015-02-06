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

namespace App;

use Dobee\FrameworkKernel\Configuration\Configuration\LoaderAbstract;
use Dobee\FrameworkKernel\FrameworkKernel;
use DemoBundle\DemoBundle;

class AppKernel extends FrameworkKernel
{
    /**
     * register project bundles into the kernel.
     *
     * @return array
     */
    public function registerBundles()
    {
        return array(
            new DemoBundle(),
        );
    }

    /**
     * register project global configuration.
     *
     * @param LoaderAbstract $loaderAbstract
     * @return mixed
     */
    public function registerContainerConfiguration(LoaderAbstract $loaderAbstract)
    {
        $loaderAbstract->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}