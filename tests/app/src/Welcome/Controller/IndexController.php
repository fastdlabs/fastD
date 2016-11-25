<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Welcome\Controller;

use FastD\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @route(method="GET", "/")
     */
    public function welcomeAction()
    {
        return $this->responseHtml('hello world');
    }

    /**
     * @route("/demo")
     */
    public function demoAction()
    {
        return $this->responseHtml('demo');
    }
}