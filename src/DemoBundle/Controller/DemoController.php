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

use Dobee\Kernel\Framework\Controller\Controller;

class DemoController extends Controller
{
    /**
     * examples:
     *      url1: http://path/to/index.php/
     *
     * @Route("/", name="demo_index")
     */
    public function demoAction()
    {
        $repository = $this->getConnection('read')->getRepository("DemoBundle:Post");

        $post = $repository->createQuery("select * from %prefix%%table% where id = :id")
            ->setParameters('id', 1)
            ->getQuery()
            ->getResult();
        ;

        return $this->render('DemoBundle:Demo:index.html.twig', array(
            'post' => $post,
        ));
    }

    /**
     * examples:
     *      url1: http://path/to/index.php/world/test
     *      url2: http://path/to/index.php/janhuang/test
     *
     * @Route("/{name}/test", name="demo_test")
     * @Route(defaults={"name": "jan"})
     */
    public function testAction($name)
    {
        return $this->render('DemoBundle:Demo:index.html.twig', array(
            'name' => $name
        ));
    }
}