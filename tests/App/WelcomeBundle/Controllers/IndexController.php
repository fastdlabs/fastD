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

class IndexController
{
    /**
     * @Route("/")
     * @Method("GET")
     *
     * @Middleware("", 50, true)
     */
    public function welcomeAction()
    {
        return new Response('hello world');
    }
}