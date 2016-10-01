<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Tests\App\WelcomeBundle\Http\Controllers;

use FastD\Http\Response;
use FastD\Standard\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * @route(method="GET", "/")
     */
    public function welcomeAction()
    {
        return new Response('hello world');
    }

    /**
     * @route("/demo", method="GET")
     */
    public function demoAction()
    {
        return $this->responseHtml('demo');
    }
}