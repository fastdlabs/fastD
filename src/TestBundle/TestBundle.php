<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 2015-02-21
 * Time: 18:09:24
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace TestBundle;

use Dobee\Kernel\Framework\Bundles\Bundle;

class TestBundle extends Bundle
{
    public function getRoutingPrefix()
    {
        return '/TestBundle';
    }
}