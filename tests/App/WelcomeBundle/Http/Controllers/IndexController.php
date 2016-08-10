<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Tests\App\WelcomeBundle\Controllers;

use FastD\Http\Response;
use FastD\Middleware\Middleware;

class IndexController
{
    /**
     * @Route("/")
     * @Method("GET")
     * @middleware(IndexController -> test)
     */
    public function welcomeAction(Middleware $test)
    {
        return new Response('hello world');
    }
}