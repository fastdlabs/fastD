<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/27
 * Time: 下午4:30
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Helpers\Demo;

use Dobee\Protocol\Http\Request;

class Demo
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function printHelloWorld()
    {
        return 'hello plugins';
    }

    public function getRequestPathInfo()
    {
        return $this->request->getPathInfo();
    }
}