<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/13
 * Time: 下午6:30
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Plugins;

use Dobee\Routing\Router;

class Demo
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }
}