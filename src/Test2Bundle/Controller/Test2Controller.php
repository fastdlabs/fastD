<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/26
 * Time: 下午2:44
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Test2Bundle\Controller;

use Dobee\Http\JsonResponse;
use Dobee\Kernel\Framework\Controller\Controller;

class Test2Controller extends Controller
{
    /**
     * 路由设置
     * @Route("/test2/{name}/{name2}", name="test2_index", defaults={"name": "janhuang"})
     */
    public function test2Action($name, $name2)
    {
        return $this->render('Test2Bundle::test.html.twig', array(
            'name' => $name,
            'name2' => $name2
        ));
    }
}