<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace WelcomeBundle\Middleware;

use FastD\Middleware\MiddlewareInterface;
use FastD\Standard\Middleware\MiddlewareAware;

class DemoMiddleware extends MiddlewareAware
{
    /**
     * @param MiddlewareInterface $prev
     * @param array $arguments
     * @param MiddlewareInterface $next
     * @return mixed
     */
    public function handle($arguments = [])
    {
        // TODO: Implement handle() method.
    }
}