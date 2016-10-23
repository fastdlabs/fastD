<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace WelcomeBundle\Controllers;

use FastD\Standard\Controllers\Controller;
use WelcomeBundle\Middleware\DemoMiddleware;

class IndexController extends Controller
{
    /**
     * @route(method="GET", "/")
     */
    public function welcomeAction()
    {
        $result = $this->middleware(DemoMiddleware::class);

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