<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/30
 * Time: 下午5:15
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Kernel;

use FastD\Config\Config;

interface AppKernelInterface
{
    /**
     * @return Bundle[]
     */
    public function getBundles();

    /**
     * Register project bundle.
     *
     * @return \Kernel\Bundle[]
     */
    public function registerBundles();

    /**
     * Register application plugins.
     *
     * @return array
     */
    public function registerHelpers();

    /**
     * Register application configuration
     *
     * @param Config $config
     * @return void
     */
    public function registerConfiguration(Config $config);

    /**
     * @return array
     */
    public function registerConfigVariable();
}