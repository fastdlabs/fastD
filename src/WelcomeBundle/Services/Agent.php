<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/5/7
 * Time: 下午12:07
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace WelcomeBundle\Services;

use FastD\Http\Request;

class Agent
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getAgent()
    {
        return $this->request->getUserAgent();
    }
}