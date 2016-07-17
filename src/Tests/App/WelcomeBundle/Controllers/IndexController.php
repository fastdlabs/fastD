<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Tests\App\WelcomeBundle\Controllers;

use FastD\Http\Response;

class IndexController
{
    /**
     * @Route("/")
     */
    public function welcomeAction()
    {
        return new Response('hello world');
    }
}