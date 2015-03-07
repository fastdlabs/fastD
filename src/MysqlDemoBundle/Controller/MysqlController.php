<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/7
 * Time: 下午3:47
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace MysqlDemoBundle\Controller;

use Dobee\Kernel\Framework\Controller\Controller;

class MysqlController extends Controller
{
    /**
     * @Route("/", name="mysql_demo")
     */
    public function demoAction()
    {
        return 'msyql demo';
    }
}