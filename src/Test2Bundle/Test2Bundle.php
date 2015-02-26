<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/7
 * Time: 上午2:02
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Test2Bundle;

use Dobee\Kernel\Framework\Bundles\Bundle;

class Test2Bundle extends Bundle
{
    public function getRoutingPrefix()
    {
        return '/test2Bundle';
    }
}