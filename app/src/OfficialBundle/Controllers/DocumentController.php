<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/22
 * Time: 上午1:18
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace OfficialBundle\Controllers;

/**
 * @Route("/docs")
 */
class DocumentController extends BaseController
{
    /**
     * @Route("/index", name="docs_index", format=["html", "php"])
     */
    public function docAction()
    {
        return $this->render('official/docs.html.twig', array(

        ));
    }
}