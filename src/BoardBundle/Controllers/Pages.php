<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/3/11
 * Time: 上午10:18
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace BoardBundle\Controllers;

/**
 * Class Pages
 *
 * @Route("/board/pages")
 * @package BoardBundle\Controllers
 */
class Pages extends Auth\AuthController
{
    /**
     * @Route("/login")
     */
    public function loginAction()
    {
        return $this->render('components/pages/login.twig');
    }
}