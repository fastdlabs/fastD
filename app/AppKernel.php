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

use DemoBundle\DemoBundle;
use Dobee\Kernel\Configuration\Configuration\LoaderInterface;
use Dobee\Kernel\Kernel;

class AppKernel extends Kernel
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
     * @param LoaderInterface $loaderInterface
     * @return mixed
     */
    public function registerContainerConfiguration(LoaderInterface $loaderInterface)
    {
        $loaderInterface->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}