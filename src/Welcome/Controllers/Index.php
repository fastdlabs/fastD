<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/14
 * Time: 下午3:23
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Welcome\Controllers;

use FastD\Framework\Bundle\Controllers\Controller;
use FastD\Http\Request;
use FastD\Http\Response;

/**
 * Class Index
 *
 * @package Welcome\Events\Http
 */
class Index extends Controller
{
    /**
     * @Route("/{name}", name="welcome", defaults={"name":"janhuang"})
     *
     * @param Request $request
     * @return Response|string
     */
    public function welcomeAction(Request $request, $name)
    {
        echo $name;
        return $this->response($this->generateUrl('welcome'));
    }
}