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

class IndexController extends Controller
{
    /**
     * @Route("/{id}", name="welcome_index", defaults={"id": 1})
     */
    public function welcomeAction($id)
    {
        return $this->render('welcome/welcome.html.twig', array(
            'version' => 'v1.0.0',
        ));
    }
}