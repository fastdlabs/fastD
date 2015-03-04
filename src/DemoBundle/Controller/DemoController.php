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
use Dobee\Kernel\Framework\Controller\Controller;

class DemoController extends Controller
{
    /**
     * examples:
     *      url1: http://path/to/index.php/
     *
     * @Route("/{name}/{age}", name="demo_index")
     * @Route(defaults={"name": "janhuang", "age": 22})
     */
    public function demoAction($name, $age)
    {
        return new JsonResponse(array(
            'name' => '大神',
            'age' => $age
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