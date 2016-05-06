<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/5/5
 * Time: 下午7:23
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace WelcomeBundle\Controllers;

use FastD\Framework\Bundle\Controllers\Controller;

/**
 * Class Demo
 *
 * @Route("/demo")
 * @package WelcomeBundle\Controllers
 */
class Demo extends Controller
{
    /**
     * @Route("/route", name="demo")
     *
     * @return \FastD\Http\Response|string
     */
    public function indexAction()
    {
        return $this->render('base/route.twig');
    }

    /**
     * @Route("/control", name="control")
     *
     * @return \FastD\Http\Response|string
     */
    public function controlAction()
    {
        return $this->render('base/control.twig');
    }
}