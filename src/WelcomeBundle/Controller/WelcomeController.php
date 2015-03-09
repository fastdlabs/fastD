<?php
/**
 * Created by PhpStorm.
 * User: JanHuang
 * Date: 2015/3/9
 * Time: 2:36
 * Email: bboyjanhuang@gmail.com
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace WelcomeBundle\Controller;

use Dobee\Kernel\Framework\Controller\Controller;

class WelcomeController extends Controller
{
    /**
     * @Route("/{author}/{company}", name="welcome_index")
     * @Route(defaults={"author": "Jan", "company": "MMC"})
     */
    public function welcomeAction($anthor, $company)
    {
        return $this->render('welcome/welcome.html.twig', array(
            'author' => $anthor,
            'company' => $company,
        ));
    }
}