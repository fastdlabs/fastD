<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/27
 * Time: 下午5:03
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Welcome\Events;

use Kernel\Events\EventAbstract;

class Route extends EventAbstract
{
    public function showRoute()
    {
        return $this->generateUrl('di');
    }
}