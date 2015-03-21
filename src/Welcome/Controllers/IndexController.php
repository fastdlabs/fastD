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

namespace Welcome\Controllers;

use Dobee\Framework\Controller\Controller;
use Dobee\Routing\Router;

class IndexController extends Controller
{
    /**
     * @Route("/", name="welcome_index")
     */
    public function welcomeAction(Router $router)
    {
        return 'hello world';
    }
}