<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/14
 * Time: 下午3:23
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Welcome\Events;

use Dobee\Framework\Bundle\Events\EventAbstract;

/**
 * Class IndexController
 *
 * @package Welcome\Controllers
 */
class Index extends EventAbstract
{
    public function welcomeAction()
    {
        $demo = $this->get('demo');

        return $demo->getHelloWorld();
    }
}