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
     * @Route("/")
     * @Method("GET")
     */
    public function welcomeAction()
    {
        return new Response('hello world');
    }
}