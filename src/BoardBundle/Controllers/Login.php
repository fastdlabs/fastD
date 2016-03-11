<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/3/10
 * Time: 下午7:18
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace BoardBundle\Controllers;

use BoardBundle\Controllers\Auth\AuthController;

/**
 * Class Login
 *
 * @Route("/board")
 * @package BoardBundle\Controllers
 */
class Login extends AuthController
{
    /**
     * @Route("/login", name="board_login")
     */
    public function loginAction()
    {
        return $this->render('components/pages/login.twig');
    }

    public function logoutAction()
    {}
}