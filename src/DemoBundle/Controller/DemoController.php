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

use Dobee\Kernel\Configuration\HttpFoundation\JsonResponse;
use Dobee\Kernel\Configuration\HttpFoundation\Request;
use Dobee\Kernel\Framework\Controller\Controller;

class DemoController extends Controller
{
    /**
     * examples:
     *      url1: http://path/to/index.php/
     *
     * @Route("/", name="demo_index")
     * @Route(defaults={"name": "janhuang", "age": 22})
     * @Route(format=["html", "json", "xml", "php"])
     */
    public function demoAction()
    {
        return $this->render('DemoBundle:Demo:index.html.twig', array(
            'name' => 'janhuang'
        ));
    }
}