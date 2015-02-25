<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 2015-02-21
 * Time: 18:09:24
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace TestBundle\Controller;

use Dobee\Kernel\Framework\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/{name}", name="TestBundle_demo", defaults={"name": "world"})
     */
    public function demoAction($name)
    {
        return $this->render('TestBundle::index.html.twig', array(
            'name' => $this->getParameters('drivers.database.default_connection'),
        ));
    }
}