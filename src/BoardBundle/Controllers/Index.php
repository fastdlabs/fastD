<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/3/10
 * Time: 下午6:30
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace BoardBundle\Controllers;

use FastD\Framework\Bundle\Controllers\Controller;

/**
 * Class Index
 *
 * @package BoardBundle\Controllers
 */
class Index extends Controller
{
    /**
     * @Route("/dashboard", name="board_index")
     */
    public function dashboardAction()
    {
        return $this->render('dashboard.html');
    }
}