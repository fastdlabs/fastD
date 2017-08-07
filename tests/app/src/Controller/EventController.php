<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace Controller;


use FastD\Event\AbstractEvent;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EventController extends AbstractEvent
{
    public function onRequest(ServerRequestInterface $request)
    {}

    public function onResponse(ResponseInterface $response)
    {}

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function handle(array $data = [])
    {
        return json(['foo' => 'bar']);
    }
}