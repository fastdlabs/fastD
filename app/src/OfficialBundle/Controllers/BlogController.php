<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/22
 * Time: 上午2:02
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace OfficialBundle\Controllers;

/**
 * @Route("/blog")
 */
class BlogController extends BaseController
{
    /**
     * @Route("/index", name="blog_index", format=["html", "php"])
     */
    public function indexAction()
    {
        return $this->render('official/blog.html.twig');
    }
}