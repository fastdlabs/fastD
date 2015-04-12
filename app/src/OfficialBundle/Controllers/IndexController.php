<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/9
 * Time: 下午3:15
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace OfficialBundle\Controllers;

use Dobee\Http\Request;

class IndexController extends BaseController
{
    /**
     * @Route("/index", name="web_index", format=["html", "php"])
     */
    public function indexAction(Request $request)
    {
        return $this->render('official/index.html.twig');
    }

    /**
     * @Route("/about", name="web_about", format=["html", "php"])
     */
    public function aboutAction()
    {
        return $this->render('official/about.html.twig');
    }

    /**
     * @Route("/showcase", name="web_showcase", format=["html", "php"])
     */
    public function showcaseAction()
    {
        return $this->render('official/showcase.html.twig');
    }

    public function downloadAction()
    {
        return $this->render('');
    }
}