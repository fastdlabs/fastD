<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Event;

use FastD\Http\Response;

class RequestListener extends EventListener
{
    /**
     * Handle event trigger
     *
     * @param EventInterface $event
     * @param array $arguments
     * @return mixed
     */
    public function handle(EventInterface $event, array $arguments = [])
    {
        (new Response('hello'))->send();
//        return $arguments[0]->get('kernel.routing')->dispatch();
    }
}