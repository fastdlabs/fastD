<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/7
 * Time: 上午2:02
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace DemoBundle\Controller;

use Dobee\FrameworkKernel\Framework\Controller\Controller;

class DemoController extends Controller
{
    /**
     * @Route("/", name="demo_index")
     */
    public function demoAction()
    {
        $repository = $this->getConnection('read')->getRepository("DemoBundle:Post");

        $post = $repository->find(1);

        return $this->render('DemoBundle:Demo:index.html.twig', array(
            'post' => $post,
            'name' => 'janhuang'
        ));
    }

    /**
     * @Route("/test/{name}", name="demo_test")
     * @Route(defaults={"name": "jan"})
     */
    public function testAction($name)
    {
        return $this->render('DemoBundle:Demo:index.html.twig', array(
            'name' => $name
        ));
    }
}